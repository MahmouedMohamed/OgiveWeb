<?php

namespace App\Http\Controllers\api;

use App\Exceptions\FoodSharingMarkerIsCollected;
use App\Exceptions\FoodSharingMarkerNotFound;
use App\Exceptions\UserNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\FoodSharingMarker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHandler;
use App\Models\AtaaAchievement;
use App\Models\AtaaPrize;
use App\Traits\ControllersTraits\FoodSharingMarkerValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class FoodSharingMarkersController extends BaseController
{
    use UserValidator, FoodSharingMarkerValidator;
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            //TODO: Get User Location -> Return Only nearest Markers
            $responseHandler = new ResponseHandler($request['language']);
            $user = $this->userExists($request['userId']);
            $userLatitude = $request['latitude'] ?? 29.9832678;
            $userLongitude = $request['longitude'] ?? 31.2282846;
            $this->userIsAuthorized($user, 'viewAny', FoodSharingMarker::class);
            $distance = "(6371 * acos(cos(radians($userLatitude))
                     * cos(radians(latitude))
                     * cos(radians(longitude)
                     - radians($userLongitude))
                     + sin(radians($userLatitude))
                     * sin(radians(latitude))))";
            return $this->sendResponse(
                // Cache::remember('foodsharingmarkers', 60 * 60 * 24, function () use($userLatitude,$userLongitude){
                // return
                FoodSharingMarker::select(
                    'id',
                    'latitude',
                    'longitude',
                    'type',
                    'description',
                    'quantity',
                    'priority'
                )
                    ->where('collected', '=', 0)
                    ->selectRaw("{$distance} AS distance")
                    ->whereRaw("{$distance} < ?", [10])
                    ->take(100)
                    ->get()
                // ;
                // })
                ,
                ''
            );
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['FoodSharingMarkerViewingBannedMessage']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $responseHandler = new ResponseHandler($request['language']);
            //Validate Request
            $validated = $this->validateMarker($request, 'store');
            if ($validated->fails()) {
                return $this->sendError($responseHandler->words['InvalidData'], $validated->messages(), 400);
            }
            $user = $this->userExists(request()->input('createdBy'));
            $this->userIsAuthorized($user, 'create', FoodSharingMarker::class);
            $userAchievement = $user->ataaAchievement;
            //No Achievements Before
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
                $highestAtaaPrize = AtaaPrize::orderBy('level', 'DESC')->where('active', '=', 1)->get()->first();

                //There is previous prize then create one with higher level
                if ($highestAtaaPrize) {
                    AtaaPrize::create([
                        'createdBy' => null,
                        'name' =>  "Level " . (((int) $highestAtaaPrize['level']) + 1) . " Prize",
                        'image' => null,
                        'required_markers_collected' => $highestAtaaPrize['required_markers_collected'] + 10,
                        'required_markers_posted' => $highestAtaaPrize['required_markers_posted'] + 10,
                        'from' => Carbon::now('GMT+2'),
                        'to' => Carbon::now('GMT+2')->add(10, 'day'),
                        'level' => $highestAtaaPrize['level'] + 1,
                    ]);
                }
                //There is no previous prize
                else {
                    AtaaPrize::create([
                        'createdBy' => null,
                        'name' =>  "Level 1 Prize",
                        'image' => null,
                        'required_markers_collected' => 0,
                        'required_markers_posted' => 5,
                        'from' => Carbon::now('GMT+2'),
                        'to' => Carbon::now('GMT+2')->add(10, 'day'),
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
                        //TODO: Maybe show the user what's left for his next milestone
                    }
                }
            }
            return $this->sendResponse([], $responseHandler->words['FoodSharingMarkerCreationSuccessMessage']);
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['FoodSharingMarkerCreationBannedMessage']);
        }
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
        try {
            $responseHandler = new ResponseHandler($request['language']);
            //Check if Marker exists
            $foodSharingMarker = $this->foodSharingMarkerExists($id);
            //Check if it has been collected to prevent fake collection
            $this->foodSharingMarkerIsCollected($foodSharingMarker);
            //Check user exists
            $user = $this->userExists($request['userId']);
            //Validate Existing value
            $foodSharingMarkerExists = $request['exists'];
            if ($foodSharingMarkerExists == null || ($foodSharingMarkerExists != 1 && $foodSharingMarkerExists != 0))
                return $this->sendError($responseHandler->words['InvalidData'], '', 400);
            if ($foodSharingMarkerExists == 0) {
                //TODO: if marker doesn't exists for 100 times for the same user
                //->
                //Ban this marker publisher for publishing again
                //TODO: Count
            }

            $foodSharingMarker->collect($foodSharingMarkerExists);

            //Check if current user is the marker creator
            if ($user->id == $foodSharingMarker->user_id) {
                //No Achievement for em
            } else {
                $userAchievement = $user->ataaAchievement;
                //No Achievements Before
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
                            'name' =>  "Level " . (((int) $highestAtaaPrize['level']) + 1) . " Prize",
                            'image' => null,
                            'required_markers_collected' => $highestAtaaPrize['required_markers_collected'] + 10,
                            'required_markers_posted' => $highestAtaaPrize['required_markers_posted'] + 10,
                            'from' => Carbon::now('GMT+2'),
                            'to' => Carbon::now('GMT+2')->add(10, 'day'),
                            'level' => $highestAtaaPrize['level'] + 1,
                        ]);
                    }
                    //There is no previous prize
                    else {
                        AtaaPrize::create([
                            'createdBy' => null,
                            'name' =>  "Level 1 Prize",
                            'image' => null,
                            'required_markers_collected' => 5,
                            'required_markers_posted' => 0,
                            'from' => Carbon::now('GMT+2'),
                            'to' => Carbon::now('GMT+2')->add(10, 'day'),
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
                            //TODO: Maybe show the user what's left for his next milestone
                        }
                    }
                }
            }

            if ($foodSharingMarkerExists == 1)
                return $this->sendResponse([], $responseHandler->words['FoodSharingMarkerSuccessCollectExist']);
            return $this->sendResponse([], $responseHandler->words['FoodSharingMarkerSuccessCollectNoExist']);
        } catch (FoodSharingMarkerNotFound $e) {
            return $this->sendError($responseHandler->words['FoodSharingMarkerNotFound']);
        } catch (FoodSharingMarkerIsCollected $e) {
            return $this->sendError($responseHandler->words['FoodSharingMarkerAlreadyCollected']);
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $responseHandler = new ResponseHandler($request['language']);
            //Validate Request
            $validated = $this->validateMarker($request, 'update');
            if ($validated->fails()) {
                return $this->sendError($responseHandler->words['InvalidData'], $validated->messages(), 400);
            }
            $foodSharingMarker = $this->foodSharingMarkerExists($id);
            $user = $this->userExists(request()->input('userId'));
            $this->userIsAuthorized($user, 'update', $foodSharingMarker);
            $foodSharingMarker->update([
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'type' => $request['type'],
                'description' => $request['description'],
                'quantity' => $request['quantity'],
                'priority' => $request['priority'],
                'collected' => 0,
            ]);
            return $this->sendResponse([], $responseHandler->words['FoodSharingMarkerUpdateSuccessMessage']);
        } catch (FoodSharingMarkerNotFound $e) {
            return $this->sendError($responseHandler->words['FoodSharingMarkerNotFound']);
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['FoodSharingMarkerUpdateForbiddenMessage']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $responseHandler = new ResponseHandler($request['language']);

            $foodSharingMarker = $this->foodSharingMarkerExists($id);
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'delete', $foodSharingMarker);
            $foodSharingMarker->delete();
            $userAchievement = $user->ataaAchievement;
            $userAchievement->decreaseMarkersPosted();
            //Get won prize by user where required is bigger than achieved after modification
            //then delete them
            DB::table('user_ataa_acquired_prizes')->leftJoin(
                'ataa_prizes',
                'ataa_prizes.id',
                '=',
                'user_ataa_acquired_prizes.prize_id'
            )->where('required_markers_posted', '>', $userAchievement->markers_posted)
                ->delete();

            return $this->sendResponse([], $responseHandler->words['FoodSharingMarkerDeleteSuccessMessage']);  ///Needy Updated Successfully!
        } catch (FoodSharingMarkerNotFound $e) {
            return $this->sendError($responseHandler->words['FoodSharingMarkerNotFound']);
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['FoodSharingMarkerDeletionForbiddenMessage']);
        }
    }

    public function validateMarker(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'createdBy' => 'required',
                    'latitude' => 'required|numeric|min:0',
                    'longitude' => 'required|numeric|min:0',
                    'type' => 'required|in:Food,Drink,Both of them',
                    'description' => 'required|max:1024',
                    'quantity' => 'required|integer|min:1|max:10',
                    'priority' => 'required|integer|min:1|max:10'
                ];
                break;
            case 'update':
                $rules = [
                    'latitude' => 'required|numeric|min:0',
                    'longitude' => 'required|numeric|min:0',
                    'type' => 'required|in:Food,Drink,Both of them',
                    'description' => 'required|max:1024',
                    'quantity' => 'required|integer|min:1|max:10',
                    'priority' => 'required|integer|min:1|max:10'
                ];
                break;
        }
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
