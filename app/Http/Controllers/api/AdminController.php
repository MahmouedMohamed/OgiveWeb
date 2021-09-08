<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\AtaaPrize;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Needy;
use App\Models\OfflineTransaction;
use App\Models\OnlineTransaction;
use App\Models\Pet;
use App\Models\UserBan;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;

class AdminController extends BaseController
{

    /**
     * Dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generalAdminDashboard(Request $request)
    {
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }
        //ToDo: Check privilige
        $data = array();
        $generalData = array();
        $ahedData = array();
        $breedmeData = array();
        $numberOfUsers = User::all()->count();
        $numberOfNeedies = Needy::all()->count();
        $numberOfNeediesSatisfied = Needy::all()->where('satisfied', '=', true)->count();
        $numberOfTransactions = OnlineTransaction::all()->count() + OfflineTransaction::all()->where('collected', '=', true)->count();
        $givesCollected = OnlineTransaction::all()->sum('amount') + OfflineTransaction::all()->where('collected', '=', true)->sum('amount');
        $numberOfPets = Pet::all()->count();
        array_push($generalData, [
            'NumberOfActiveUsers' => $numberOfUsers,
        ]);
        array_push($ahedData, [
            'NumberOfNeedies' => $numberOfNeedies,
            'NumberOfNeediesSatisfied' => $numberOfNeediesSatisfied,
            'NumberOfTransactions' => $numberOfTransactions,
            'NumberOfGives' => $givesCollected,
        ]);
        array_push($breedmeData, [
            'NumberOfPets' => $numberOfPets
        ]);
        array_push($data, [
            'General' => $generalData,
            'Ahed' => $ahedData,
            'BreedMe' => $breedmeData,
        ]);
        return $this->sendResponse($data, 'Data Retrieved Successfully!');
    }

    /**
     * Approve Case.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('Needy Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can approve
        if (!$user->can('approve', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to approve this needy.');
        }
        $needy->approve();
        return $this->sendResponse([], 'Needy Approved Successfully!');
    }

    /**
     * Disapprove Case.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disapprove(Request $request, $id)
    {
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('Needy Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can disapprove
        if (!$user->can('disapprove', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to disapprove this needy.');
        }
        $needy->disapprove();
        return $this->sendResponse([], 'Needy Disapprove Successfully!');
    }

    /**
     * Collect offline transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function collectOfflineTransaction(Request $request)
    {
        //Check needy exists
        $offlinetransaction = OfflineTransaction::find($request['transactionId']);
        if ($offlinetransaction == null) {
            return $this->sendError('Transaction Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can approve
        if (!$user->can('collect', $offlinetransaction)) {
            return $this->sendForbidden('You aren\'t authorized to collect this transaction.');
        }
        $offlinetransaction->collect();
        return $this->sendResponse([], 'Transaction Collected Successfully!');
    }

    /**
     * Freeze Ataa Achievment for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function freezeUserAtaaAchievments(Request $request)
    {
        //Check User exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check user "Admin" who is updating exists
        $admin = User::find($request['adminId']);
        if ($admin == null) {
            return $this->sendError('Admin User Not Found');
        }

        //Check if current user can freeze
        if (!$admin->can('freeze', $user->ataaAchievement)) {
            return $this->sendForbidden('You aren\'t authorized to freeze this user acheivement.');
        }

        //Check if user has achievment
        if (!$user->ataaAchievement) {
            return $this->sendError('User Achievement doesn\'t exist');
        }

        $user->ataaAchievement->freeze();
        return $this->sendResponse([], 'User Acheivement Freezed Successfully!');
    }

    /**
     * Defreeze Ataa Achievment for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function defreezeUserAtaaAchievments(Request $request)
    {
        //Check User exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check user "Admin" who is updating exists
        $admin = User::find($request['adminId']);
        if ($admin == null) {
            return $this->sendError('Admin User Not Found');
        }

        //Check if current user can freeze
        if (!$admin->can('defreeze', $user->ataaAchievement)) {
            return $this->sendForbidden('You aren\'t authorized to defreeze this user acheivement.');
        }

        //Check if user has achievment
        if (!$user->ataaAchievement) {
            return $this->sendError('User Achievement doesn\'t exist');
        }

        $user->ataaAchievement->defreeze();
        return $this->sendResponse([], 'User Acheivement Defreezed Successfully!');
    }

    /**
     * Add Ataa Prize.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAtaaPrize(Request $request)
    {

        //Check user "Admin" who is updating exists
        $admin = User::find($request['createdBy']);
        if ($admin == null) {
            return $this->sendError('Admin User Not Found');
        }

        //Check if current user can create
        if (!$admin->can('create', AtaaPrize::class)) {
            return $this->sendForbidden('You aren\'t authorized to create a Prize.');
        }
        $validated = $this->validateAtaaPrize($request);
        if ($validated->fails()) {
            return $this->sendError('Invalid data', $validated->messages(), 400);
        }

        try {
            $sameLevelPrize = AtaaPrize::where('active', '=', 1)->where('level', '=', $request['level'])->get()->first();
            if ($sameLevelPrize) {

                //Check if admin want to replace or shift
                $adminChosenAction = $request['action'];
                if ($adminChosenAction == null || ($adminChosenAction != 'replace' && $adminChosenAction != 'shift'))
                    return $this->sendError('Invalid value for action', $validated->messages(), 400);


                //replace => deactivate the old prize, create the new
                if ($adminChosenAction == 'replace') {
                    $sameLevelPrize->deactivate();

                    $imagePath = null;
                    if ($request['image']) {
                        $imagePath = $request['image']->store('ataa_prizes', 'public');
                        $imagePath = "/storage/" . $imagePath;
                    }
                    AtaaPrize::create([
                        'createdBy' => $request['createdBy'],
                        'name' => $request['name'],
                        'image' => $imagePath,
                        'required_markers_collected' => $request['required_markers_collected'],
                        'required_markers_posted' => $request['required_markers_posted'],
                        'from' => $request['from'] ?? Carbon::now(),
                        'to' => $request['to'],
                        'level' => $request['level'],
                    ]);
                } else {
                    //shift the others where level is bigger
                    $biggerLevelPrizes = AtaaPrize::where('active', '=', 1)->where('level', '>=', $sameLevelPrize['level'])->get();
                    foreach ($biggerLevelPrizes as $prize) {
                        //update their level
                        $prize->increaseLevel();
                        //update their name if they are auto filled
                        if (str_contains($prize['name'], 'Level'))
                            $prize->updateName();
                    }
                    //create New
                    $imagePath = null;
                    if ($request['image']) {
                        $imagePath = $request['image']->store('ataa_prizes', 'public');
                        $imagePath = "/storage/" . $imagePath;
                    }
                    AtaaPrize::create([
                        'createdBy' => $request['createdBy'],
                        'name' => $request['name'],
                        'image' => $imagePath,
                        'required_markers_collected' => $request['required_markers_collected'],
                        'required_markers_posted' => $request['required_markers_posted'],
                        'from' => $request['from'] ?? Carbon::now(),
                        'to' => $request['to'],
                        'level' => $request['level'],
                    ]);
                }
            } else {
                $imagePath = null;
                if ($request['image']) {
                    $imagePath = $request['image']->store('ataa_prizes', 'public');
                    $imagePath = "/storage/" . $imagePath;
                }
                AtaaPrize::create([
                    'createdBy' => $request['createdBy'],
                    'name' => $request['name'],
                    'image' => $imagePath,
                    'required_markers_collected' => $request['required_markers_collected'],
                    'required_markers_posted' => $request['required_markers_posted'],
                    'from' => $request['from'] ?? Carbon::now(),
                    'to' => $request['to'],
                    'level' => $request['level'],
                ]);
            }
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
        return $this->sendResponse([], 'Ataa Prize Created Successfully!');
    }

    /**
     * Get User Bans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserBans(Request $request)
    {
        //Check user exists
        $admin = User::find($request['userId']);
        if ($admin == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can get List of User Bans
        if (!$admin->can('viewAny', UserBan::class)) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }

        return $this->sendResponse(UserBan::all(), 'User Bans Retrieved Successfully');
    }
    public function validateAtaaPrize(Request $request)
    {

        $rules = [
            'createdBy' => 'required',
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'required_markers_collected' => 'required|integer|min:0',
            'required_markers_posted' => 'required|integer|min:0',
            'from' => 'date',
            'to' => 'date|after:from',
            'level' => 'required|integer|min:1',
        ];
        $messages = [
            'required' => 'This field is required',
            'min' => 'Wrong value, minimum value is :min',
            'max' => 'Wrong size, maximum size is :max',
            'integer' => 'Wrong value, supports only real numbers',
            'in' => 'Wrong value, supported values are :values',
            'numeric' => 'Wrong value, supports only numeric numbers',
            'image' => 'Wrong value, supports only images',
            'mimes' => 'Wrong value, supports only :values',
            'date' => 'Wrong value, supports only date',
            'before' => 'The :attribute must be before :date',
            'after' => 'The :attribute must be after :date'
        ];
        return Validator::make($request->all(), $rules, $messages);
    }
}
