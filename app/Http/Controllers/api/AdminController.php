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
use App\Models\BanType;

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
        //TODO: Check privilige
        $data = array();
        $generalData = array();
        $ahedData = array();
        $breedmeData = array();
        $numberOfUsers = User::count();
        $numberOfNeedies = Needy::count();
        $numberOfNeediesSatisfied = Needy::where('satisfied', '=', true)->count();
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
    public function freezeUserAtaaAchievements(Request $request)
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
            return $this->sendForbidden('You aren\'t authorized to freeze this user achievement.');
        }

        //Check if user has achievement
        if (!$user->ataaAchievement) {
            return $this->sendError('User Achievement doesn\'t exist');
        }

        $user->ataaAchievement->freeze();
        return $this->sendResponse([], 'User Achievement Freezed Successfully!');
    }

    /**
     * Defreeze Ataa Achievement for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function defreezeUserAtaaAchievements(Request $request)
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
            return $this->sendForbidden('You aren\'t authorized to defreeze this user achievements.');
        }

        //Check if user has achievement
        if (!$user->ataaAchievement) {
            return $this->sendError('User Achievement doesn\'t exist');
        }

        $user->ataaAchievement->defreeze();
        return $this->sendResponse([], 'User Achievements Defreezed Successfully!');
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

    /**
     * activate User Bans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function activateBan(Request $request, int $id)
    { //Check UserBan exists
        $userBan = UserBan::find($id);
        if ($userBan == null) {
            return $this->sendError('User Ban doesn\'t exist');
        }
        //Check admin exists
        $admin = User::find($request['userId']);
        if ($admin == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can Activate User Ban
        if (!$admin->can('activate', $userBan)) {
            return $this->sendForbidden('You aren\'t authorized to activate the ban.');
        }

        $userBan->activate();

        return $this->sendResponse('', 'User Ban Activated Successfully');
    }

    /**
     * deactivate User Bans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deactivateBan(Request $request, int $id)
    {
        //Check UserBan exists
        $userBan = UserBan::find($id);
        if ($userBan == null) {
            return $this->sendError('User Ban doesn\'t exist');
        }

        //Check admin exists
        $admin = User::find($request['userId']);
        if ($admin == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can Deactivate User Ban
        if (!$admin->can('deactivate', $userBan)) {
            return $this->sendForbidden('You aren\'t authorized to deactivate the ban.');
        }

        $userBan->deactivate();

        return $this->sendResponse('', 'User Ban Deactivated Successfully');
    }

    /**
     * Add User Ban.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUserBan(Request $request)
    {
        //Check admin exists
        $admin = User::find($request['userId']);
        if ($admin == null) {
            return $this->sendError('Admin Not Found');
        }

        //Check banned User exists
        $bannedUser = User::find($request['bannedUser']);
        if ($bannedUser == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can Deactivate User Ban
        if (!$admin->can('create', [UserBan::class, $bannedUser])) {
            return $this->sendForbidden('You aren\'t authorized to create the ban.');
        }


        $validated = $this->validateUserBan($request);
        if ($validated->fails()) {
            return $this->sendError('Invalid data', $validated->messages(), 400);
        }

        //TODO: Extend Ban if already exists & Active?

        $admin->createdBans()->create([
            'banned_user' => $bannedUser->id,
            'tag' => $request['tag'],
            'active' => $request['startAt'] != null ? ($request['startAt'] <= Carbon::now('GMT+2') ? 1 : 0) : 1,
            'start_at' => $request['startAt'] ?? Carbon::now('GMT+2'),
            'end_at' => $request['endAt'] ?? null
        ]);

        return $this->sendResponse('', 'User Ban Created Successfully');
    }



    public function validateUserBan(Request $request)
    {
        $rules = [
            'tag' => 'required',
            'start_at' => 'date',
            'end_at' => 'date|after:from'
        ];
        $messages = [
            'required' => 'This field is required',
            'date' => 'Wrong value, supports only date',
            'after' => 'The :attribute must be after :date'
        ];
        return Validator::make($request->all(), $rules, $messages);
    }
}
