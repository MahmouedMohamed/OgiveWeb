<?php

namespace App\Http\Controllers\api;

use App\Exceptions\AtaaAchievementNotFound;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\NotSupportedType;
use App\Exceptions\OfflineTransactionNotFound;
use App\Exceptions\UserBanNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Needy;
use App\Models\OauthAccessToken;
use App\Models\OfflineTransaction;
use App\Models\OnlineTransaction;
use App\Models\Pet;
use App\Models\UserBan;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ControllersTraits\NeedyValidator;
use App\Traits\ControllersTraits\OfflineTransactionValidator;
use App\Traits\ControllersTraits\UserBanValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Support\Facades\DB;

class AdminController extends BaseController
{
    use UserValidator, NeedyValidator, OfflineTransactionValidator, UserBanValidator;

    /**
     * Dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generalAdminDashboard(Request $request)
    {
        try {
            $user = User::find($request['userId']);
            //TODO: Check privilige
            $numberOfUsers = User::count();

            //Get users created in the last 6 years
            $numberOfJoinedUsersByYear = User::selectRaw('count(*) as count, YEAR(created_at) year')
                ->where('created_at', '>=', Carbon::now()->subYears(6)->year)
                ->groupBy('year')
                ->orderBy('year', 'ASC')
                ->get();

            $numberOfUsersGroupedByNationality = User::selectRaw('count(*) as count, nationality')
                ->groupBy('nationality')
                //take first 6 only
                ->take(6)
                ->orderBy('count', 'ASC')
                ->get();

            $numberOfActiveUsersGroupedByAccessType = OauthAccessToken::selectRaw('count(*) as count, accessType')
                ->groupBy('accessType')
                ->where('active', '=', 1)
                ->orderBy('count', 'ASC')
                ->get();

            $numberOfActiveUsersGroupedByAppType = OauthAccessToken::selectRaw('count(*) as count, appType')
                ->groupBy('appType')
                ->where('active', '=', 1)
                ->orderBy('count', 'ASC')
                ->get();

            //BreedMe Data
            $pets = Pet::get();

            //Ahed Data
            $needies = Needy::select('id', 'satisfied')->get();
            $numberOfNeedies = $needies->count();
            $numberOfNeediesSatisfied = $needies->where('satisfied', '=', true)->count();
            $offlineTransactions = OfflineTransaction::select('id', 'amount', 'collected')->get();
            $onlineTransactions = OnlineTransaction::select('id', 'amount')->get();
            $numberOfTransactions = $onlineTransactions->count() + $offlineTransactions->where('collected', '=', true)->count();
            $givesCollected = $onlineTransactions->sum('amount') + $offlineTransactions->where('collected', '=', true)->sum('amount');

            //Ataa Data

            //Finalize
            return $this->sendResponse([
                'General' => [
                    'NumberOfActiveUsers' => $numberOfUsers,
                    'NumberOfActiveUsersGroupedByAccessType' => $numberOfActiveUsersGroupedByAccessType,
                    'NumberOfActiveUsersGroupedByAppType' => $numberOfActiveUsersGroupedByAppType,
                    'NumberOfJoinedUsersByYear' => $numberOfJoinedUsersByYear,
                    'NumberOfUsersGroupedByNationality' => $numberOfUsersGroupedByNationality
                ],
                'Ahed' => [
                    'NumberOfNeedies' => $numberOfNeedies,
                    'NumberOfNeediesSatisfied' => $numberOfNeediesSatisfied,
                    'NumberOfTransactions' => $numberOfTransactions,
                    'NumberOfGives' => $givesCollected,
                ],
                'BreedMe' => [
                    'NumberOfPets' => $pets->count()
                ],
            ], 'Data Retrieved Successfully!');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        }
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
        try {
            $user = $this->userExists($request['userId']);
            $needy = $this->needyExists($id);
            $this->userIsAuthorized($user, 'approve', $needy);
            $needy->approve();
            return $this->sendResponse([], 'Needy Approved Successfully!');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to approve this needy.');
        } catch (NeedyNotFound $e) {
            return $this->sendError('Needy Not Found');
        }
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
        try {
            $user = $this->userExists($request['userId']);
            $needy = $this->needyExists($id);
            $this->userIsAuthorized($user, 'disapprove', $needy);
            $needy->disapprove();
            return $this->sendResponse([], 'Needy Disapprove Successfully!');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to disapprove this needy.');
        } catch (NeedyNotFound $e) {
            return $this->sendError('Needy Not Found');
        }
    }

    /**
     * Collect offline transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function collectOfflineTransaction(Request $request)
    {
        try {
            $user = $this->userExists($request['userId']);
            $offlinetransaction = $this->offlineTransactionExists($request['transactionId']);
            $this->userIsAuthorized($user, 'collect', $offlinetransaction);
            $offlinetransaction->collect();
            return $this->sendResponse([], 'Transaction Collected Successfully!');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to collect this transaction.');
        } catch (OfflineTransactionNotFound $e) {
            return $this->sendError('Transaction Not Found');
        }
    }

    /**
     * Freeze Ataa Achievment for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function freezeUserAtaaAchievements(Request $request)
    {
        try {
            $user = $this->userExists($request['userId']);
            $admin = $this->userExists($request['adminId']);
            $this->userIsAuthorized($admin, 'freeze', $user->ataaAchievement);
            $user->ataaAchievement->freeze();
            return $this->sendResponse([], 'User Achievement Freezed Successfully!');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to freeze this user achievement.');
        } catch (AtaaAchievementNotFound $e) {
            return $this->sendError('User Achievement doesn\'t exist');
        }
    }

    /**
     * Defreeze Ataa Achievement for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function defreezeUserAtaaAchievements(Request $request)
    {
        try {
            $user = $this->userExists($request['userId']);
            $admin = $this->userExists($request['adminId']);
            $this->userIsAuthorized($admin, 'defreeze', $user->ataaAchievement);
            $user->ataaAchievement->defreeze();
            return $this->sendResponse([], 'User Achievement Defreezed Successfully!');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to defreeze this user achievement.');
        } catch (AtaaAchievementNotFound $e) {
            return $this->sendError('User Achievement doesn\'t exist');
        }
    }

    /**
     * Get User Bans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserBans(Request $request)
    {
        try {
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'viewAny', UserBan::class);
            return $this->sendResponse(UserBan::all(), 'User Bans Retrieved Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }
    }

    /**
     * activate User Bans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function activateBan(Request $request, int $id)
    {
        try {
            $user = $this->userExists($request['userId']);
            $userBan = $this->userBanExists($id);
            $this->userIsAuthorized($user, 'activate', $userBan);
            $userBan->activate();
            return $this->sendResponse('', 'User Ban Activated Successfully');
        } catch (UserBanNotFound $e) {
            return $this->sendError('User Ban doesn\'t exist');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }
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
        try {
            $user = $this->userExists($request['userId']);
            $userBan = $this->userBanExists($id);
            $this->userIsAuthorized($user, 'deactivate', $userBan);
            $userBan->deactivate();
            return $this->sendResponse('', 'User Ban Deactivated Successfully');
        } catch (UserBanNotFound $e) {
            return $this->sendError('User Ban doesn\'t exist');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }
    }

    /**
     * Add User Ban.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUserBan(Request $request)
    {
        try {
            $admin = $this->userExists($request['userId']);
            $bannedUser = $this->userExists($request['bannedUser']);
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
        } catch (UserBanNotFound $e) {
            return $this->sendError('User Ban doesn\'t exist');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }
    }

    public function importCSV(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'type' => 'required',
            'file' => 'required|mimes:csv,txt',
        ]);
        if ($validated->fails()) {
            return $this->sendError('Invalid data', $validated->messages(), 400);
        }
        $now = Carbon::now()->toDateTimeString();
        try {
            switch ($request['type']) {
                case 'OnlineTransaction':
                    $file = fopen($request->file->getRealPath(), 'r');
                    $onlineTransactions = [];
                    $users = collect([]);
                    $needies = collect([]);
                    while ($csvLine = fgetcsv($file)) {
                        if (!($users->pluck('id')->has($csvLine[0]))) {
                            $user = $this->userExists($csvLine[0]);
                            $users->push($user);
                        }
                        if (!($needies->pluck('id')->has($csvLine[1]))) {
                            $needy = $this->userExists($csvLine[1]);
                            $needies->push($needy);
                        }
                        $onlineTransactions[] = [
                            'giver' => $csvLine[0],
                            'needy' => $csvLine[1],
                            'amount' => $csvLine[2],
                            'remaining' => $csvLine[3],
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                    }
                    OnlineTransaction::insert($onlineTransactions);
                    break;
                default:
                    throw new NotSupportedType();
            }
            return $this->sendResponse('', 'CSV Imported Successfully');
        } catch (NotSupportedType $e) {
            return $this->sendError('This type isn\'t supported');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (NeedyNotFound $e) {
            return $this->sendError('Needy Not Found');
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 400);
        }
    }
}
