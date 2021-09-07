<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\FoodSharingMarker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHandler;
use App\Models\AtaaAchievement;
use App\Models\AtaaPrize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class FoodSharingMarkersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //ToDo: Get User Location -> Return Only nearest Markers
        return $this->sendResponse(FoodSharingMarker::select(
            'id',
            'latitude',
            'longitude',
            'type',
            'description',
            'quantity',
            'priority'
        )
            ->where('collected', '=', 0)->get(), '');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $responseHandler = new ResponseHandler($request['language']);
        //Validate Request
        $validated = $this->validateMarker($request);
        if ($validated->fails()) {
            return $this->sendError($responseHandler->words['InvalidData'], $validated->messages(), 400);
        }

        $user = User::find(request()->input('createdBy'));
        if (!$user) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        }

        $userAchievement = $user->ataaAchievement;
        //No Acheivements Before
        if (!$userAchievement) {
            $userAchievement = $user->ataaAchievement()->create([
                'markers_collected' => 0,
                'markers_posted' => 1
            ]);
        } else {
            $userAchievement->incrementMarkersPosted();
        }

        $user->foodSharingMarkers()->create([
            'latitude' => $request['latitude'],
            'longitude' => $request['longitude'],
            'type' => $request['type'],
            'description' => $request['description'],
            'quantity' => $request['quantity'],
            'priority' => $request['priority'],
            'collected' => 0,
        ]);

        ///Prize Checker

        //Returns Active Prizes Not Acquired By User "Same Level Checker"
        $ataaActivePrizes = AtaaPrize::where('active', '=', 1)
            ->whereNotIn(
                'level',
                DB::table('ataa_prizes')->leftJoin(
                    'user_ataa_acquired_prizes',
                    'ataa_prizes.id',
                    '=',
                    'user_ataa_acquired_prizes.prize_id'
                )->where('user_id', $user->id)->select('level')->get()->pluck('level')
            )
            ->get();

        //If Empty -> no previous prizes || user acquired all -> Auto Create new one with higher value
        if ($ataaActivePrizes->isEmpty()) {
            $highestAtaaPrize = AtaaPrize::orderBy('id', 'DESC')->where('active', '=', 1)->get()->first();

            //There is previous prize then create one with higher level
            if ($highestAtaaPrize) {
                AtaaPrize::create([
                    'createdBy' => null,
                    'name' =>  "Level " . $highestAtaaPrize['level'] + 1 . " Prize",
                    'image' => null,
                    'required_markers_collected' => $highestAtaaPrize['required_markers_collected'] + 10,
                    'required_markers_posted' => $highestAtaaPrize['required_markers_posted'] + 10,
                    'from' => Carbon::now(),
                    'to' => Carbon::now()->add(10, 'day'),
                    'level' => $highestAtaaPrize['level'] + 1,
                ]);
            }
            //There is no previous prize
            else {
                AtaaPrize::create([
                    'createdBy' => null,
                    'name' =>  "Level 1" . " Prize",
                    'image' => null,
                    'required_markers_collected' => 0,
                    'required_markers_posted' => 5,
                    'from' => Carbon::now(),
                    'to' => Carbon::now()->add(10, 'day'),
                    'level' => 1,
                ]);
            }
        }
        //There is prizes exists & Not acquired By User
        else {
            foreach ($ataaActivePrizes as $prize) {
                if ($prize['required_markers_collected'] <= $userAchievement['markers_collected'] && $prize['required_markers_posted'] <= $userAchievement['markers_posted']) {
                    $prize->winners()->attach(
                        $user->id
                    );
                } else {
                    //ToDo: Maybe show the user what's left for his next milestone
                }
            }
        }

        return $this->sendResponse([], $responseHandler->words['FoodSharingMarkerCreationSuccessMessage']);
    }

    /**
     * Collect Food Sharing Marker.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function collect(Request $request, $id)
    {
        $responseHandler = new ResponseHandler($request['language']);

        //Check if Marker exists
        $foodSharingMarker = FoodSharingMarker::find($id);
        if ($foodSharingMarker == null) {
            return $this->sendError($responseHandler->words['FoodSharingMarkerNotFound']);
        }

        //Check if it has been collected to prevent fake collection
        if ($foodSharingMarker->collected == 1) {
            return $this->sendError($responseHandler->words['FoodSharingMarkerAlreadyCollected']);
        }

        //Check user exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        }

        //Validate Existing value
        $foodSharingMarkerExists = $request['exists'];
        if ($foodSharingMarkerExists == null || ($foodSharingMarkerExists != 1 && $foodSharingMarkerExists != 0))
            return $this->sendError($responseHandler->words['InvalidData'], '', 400);

        if ($foodSharingMarkerExists == 0) {
            //ToDo: if marker doesn't exists for 100 times for the same user
            //->
            //Ban this marker publisher for publishing again
            //ToDo: Count
        }

        $foodSharingMarker->collect($foodSharingMarkerExists);

        //Check if current user is the marker creator
        if ($user->id == $foodSharingMarker->user_id) {
            //No Achievement for em
        } else {
            $userAchievement = $user->ataaAchievement;
            //No Acheivements Before
            if (!$userAchievement) {
                $userAchievement = $user->ataaAchievement()->create([
                    'markers_collected' => 1,
                    'markers_posted' => 0
                ]);
            } else {
                $userAchievement->incrementMarkersCollected();
            }


            //Prize Checker
            //Returns Active Prizes Not Acquired By User "Same Level Checker"
            $ataaActivePrizes = AtaaPrize::where('active', '=', 1)
                ->whereNotIn(
                    'level',
                    DB::table('ataa_prizes')->leftJoin(
                        'user_ataa_acquired_prizes',
                        'ataa_prizes.id',
                        '=',
                        'user_ataa_acquired_prizes.prize_id'
                    )->where('user_id', $user->id)->select('level')->get()->pluck('level')
                )
                ->get();

            //If Empty -> no previous prizes || user acquired all -> Auto Create new one with higher value
            if ($ataaActivePrizes->isEmpty()) {
                $highestAtaaPrize = AtaaPrize::orderBy('level', 'DESC')->where('active', '=', 1)->get()->first();
                //There is previous prize then create one with higher level
                if ($highestAtaaPrize) {
                    AtaaPrize::create([
                        'createdBy' => null,
                        'name' =>  "Level " . $highestAtaaPrize['level'] + 1 . " Prize",
                        'image' => null,
                        'required_markers_collected' => $highestAtaaPrize['required_markers_collected'] + 10,
                        'required_markers_posted' => $highestAtaaPrize['required_markers_posted'] + 10,
                        'from' => Carbon::now(),
                        'to' => Carbon::now()->add(10, 'day'),
                        'level' => $highestAtaaPrize['level'] + 1,
                    ]);
                }
                //There is no previous prize
                else {
                    AtaaPrize::create([
                        'createdBy' => null,
                        'name' =>  "Level " . $request['level'] . " Prize",
                        'image' => null,
                        'required_markers_collected' => 5,
                        'required_markers_posted' => 0,
                        'from' => Carbon::now(),
                        'to' => Carbon::now()->add(10, 'day'),
                        'level' => 1,
                    ]);
                }
            }
            //There is prizes exists & Not acquired By User
            else {
                foreach ($ataaActivePrizes as $prize) {
                    if ($prize['required_markers_collected'] <= $userAchievement['markers_collected'] && $prize['required_markers_posted'] <= $userAchievement['markers_posted']) {
                        $prize->winners()->attach(
                            $user->id
                        );
                    } else {
                        //ToDo: Maybe show the user what's left for his next milestone
                    }
                }
            }
        }

        if ($foodSharingMarkerExists == 1)
            return $this->sendResponse([], $responseHandler->words['FoodSharingMarkerSuccessCollectExist']);
        return $this->sendResponse([], $responseHandler->words['FoodSharingMarkerSuccessCollectNoExist']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return \Illuminate\Http\Response
     */
    public function show(FoodSharingMarker $foodSharingMarker)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodSharingMarker $foodSharingMarker)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodSharingMarker $foodSharingMarker)
    {
        return $this->sendError('Not Implemented');
    }

    public function validateMarker(Request $request)
    {
        $rules = [
            'createdBy' => 'required',
            'latitude' => 'required|numeric|min:0',
            'longitude' => 'required|numeric|min:0',
            'type' => 'required|in:Food,Drink,Both of them',
            'description' => 'required|max:1024',
            'quantity' => 'required|integer|min:1|max:10',
            'priority' => 'required|integer|min:1|max:10'
        ];
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }

    public function getValidatorMessagesBasedOnLanguage(string $language)
    {
        if ($language == 'En')
            return [
                'required' => 'This field is required',
                'min' => 'Wrong value, minimum value is :min',
                'max' => 'Wrong value, maximum value is :max',
                'integer' => 'Wrong value, supports only real numbers',
                'in' => 'Wrong value, supported values are :values',
                'numeric' => 'Wrong value, supports only numeric numbers',
            ];
        else if ($language == 'Ar')
            return [
                'required' => 'هذا الحقل مطلوب',
                'min' => 'قيمة خاطئة، أقل قيمة هي :min',
                'max' => 'قيمة خاطئة أعلي قيمة هي :max',
                'integer' => 'قيمة خاطئة، فقط يمكن قبول الأرقام فقط',
                'in' => 'قيمة خاطئة، القيم المتاحة هي :values',
                'image' => 'قيمة خاطئة، يمكن قبول الصور فقط',
                'mimes' => 'يوجد خطأ في النوع، الأنواع المتاحة هي :values',
                'numeric' => 'قيمة خاطئة، يمكن قبول الأرقام فقط',
            ];
    }
}
