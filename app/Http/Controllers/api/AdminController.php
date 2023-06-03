<?php

namespace App\Http\Controllers\api;

use App\Exceptions\NotSupportedType;
use App\Exceptions\UserNotAuthorized;
use App\Http\Requests\CreateUserBanRequest;
use App\Http\Requests\ImportCSVRequest;
use App\Models\Ahed\Needy;
use App\Models\Ahed\OfflineTransaction;
use App\Models\Ahed\OnlineTransaction;
use App\Models\Ataa\AtaaAchievement;
use App\Models\BreedMe\Pet;
use App\Models\OauthAccessToken;
use App\Models\User;
use App\Models\UserBan;
use App\Traits\ControllersTraits\NeedyValidator;
use App\Traits\ControllersTraits\UserValidator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    use UserValidator, NeedyValidator;

    /**
     * Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function generalAdminDashboard(Request $request)
    {
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

        $numberOfActiveUsersGroupedByAccessType = OauthAccessToken::selectRaw('count(*) as count, access_type')
            ->groupBy('access_type')
            ->where('active', '=', 1)
            ->orderBy('count', 'ASC')
            ->get();

        $numberOfActiveUsersGroupedByAppType = OauthAccessToken::selectRaw('count(*) as count, app_type')
            ->groupBy('app_type')
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
                'NumberOfUsersGroupedByNationality' => $numberOfUsersGroupedByNationality,
            ],
            'Ahed' => [
                'NumberOfNeedies' => $numberOfNeedies,
                'NumberOfNeediesSatisfied' => $numberOfNeediesSatisfied,
                'NumberOfTransactions' => $numberOfTransactions,
                'NumberOfGives' => $givesCollected,
            ],
            'BreedMe' => [
                'NumberOfPets' => $pets->count(),
            ],
        ], __('General.DataRetrievedSuccessMessage'));
    }

    /**
     * List Ataa Achievement for All Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAtaaAchievements(Request $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'viewAny', AtaaAchievement::class);

            return $this->sendResponse(AtaaAchievement::all(), 'Data Retrieved Successfully');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to show these resources.');
        }
    }

    /**
     * Approve Case.
     *
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, Needy $needy)
    {
        try {
            $this->userIsAuthorized($request->user, 'approve', $needy);
            $needy->approve();

            return $this->sendResponse([], 'Needy Approved Successfully!');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to approve this needy.');
        }
    }

    /**
     * Disapprove Case.
     *
     * @return \Illuminate\Http\Response
     */
    public function disapprove(Request $request, Needy $needy)
    {
        try {
            $this->userIsAuthorized($request->user, 'disapprove', $needy);
            $needy->disapprove();

            return $this->sendResponse([], 'Needy Disapprove Successfully!');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to disapprove this needy.');
        }
    }

    /**
     * Collect offline transaction.
     *
     * @param  App\Models\Ahed\OfflineTransaction  $offlineTransaction
     * @return \Illuminate\Http\Response
     */
    public function collectOfflineTransaction(Request $request, OfflineTransaction $offlineTransaction)
    {
        try {
            $this->userIsAuthorized($request->user, 'collect', $offlineTransaction);
            $offlineTransaction->collect();

            return $this->sendResponse([], 'Transaction Collected Successfully!');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to collect this transaction.');
        }
    }

    /**
     * Freeze Ataa Achievment for a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function freezeUserAtaaAchievements(Request $request, User $user)
    {
        try {
            $this->userIsAuthorized($request->user, 'freeze', $user->ataaAchievement);
            $user->ataaAchievement->freeze();

            return $this->sendResponse([], 'User Achievement Freezed Successfully!');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to freeze this user achievement.');
        }
    }

    /**
     * Defreeze Ataa Achievement for a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function defreezeUserAtaaAchievements(Request $request, User $user)
    {
        try {
            $this->userIsAuthorized($request->user, 'defreeze', $user->ataaAchievement);
            $user->ataaAchievement->defreeze();

            return $this->sendResponse([], 'User Achievement Defreezed Successfully!');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to defreeze this user achievement.');
        }
    }

    /**
     * Get User Bans.
     *
     * @param  App\Models\BaseUserModel  $bannedUser
     * @return \Illuminate\Http\Response
     */
    public function getUserBans(Request $request, User $bannedUser)
    {
        try {
            $this->userIsAuthorized($request->user, 'viewAny', UserBan::class);

            return $this->sendResponse($bannedUser->bans, 'User Bans Retrieved Successfully');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }
    }

    /**
     * activate User Bans.
     *
     * @return \Illuminate\Http\Response
     */
    public function activateBan(Request $request, UserBan $userBan)
    {
        try {
            $this->userIsAuthorized($request->user, 'activate', $userBan);
            $userBan->activate();

            return $this->sendResponse('', 'User Ban Activated Successfully');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }
    }

    /**
     * deactivate User Bans.
     *
     * @return \Illuminate\Http\Response
     */
    public function deactivateBan(Request $request, UserBan $userBan)
    {
        try {
            $this->userIsAuthorized($request->user, 'deactivate', $userBan);
            $userBan->deactivate();

            return $this->sendResponse('', 'User Ban Deactivated Successfully');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }
    }

    /**
     * Add User Ban.
     *
     * @param  App\Models\BaseUserModel  $bannedUser
     * @return \Illuminate\Http\Response
     */
    public function addUserBan(CreateUserBanRequest $request, User $bannedUser)
    {
        try {
            //Check if current user can Deactivate User Ban
            $this->userIsAuthorized($request->user, 'create', [UserBan::class, $bannedUser]);
            //TODO: Extend Ban if already exists & Active?
            $request->user->createdBans()->create([
                'banned_user' => $bannedUser->id,
                'tag' => $request['tag'],
                'active' => $request['startAt'] != null ? ($request['startAt'] <= Carbon::now('GMT+2') ? 1 : 0) : 1,
                'start_at' => $request['startAt'] ?? Carbon::now('GMT+2'),
                'end_at' => $request['endAt'] ?? null,
            ]);

            return $this->sendResponse('', 'User Ban Created Successfully');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to see these resources.');
        }
    }

    /**
     * Display a listing of all Needies.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingNeedies()
    {
        return $this->sendResponse(
            Needy::where('approved', '=', 0)
                ->with(['createdBy.profile', 'mediasBefore:id,path,needy_id', 'mediasAfter:id,path,needy_id'])
                ->paginate(8),
            'تم إسترجاع البيانات بنجاح'
        );  ///Cases retrieved successfully.
    }

    //ToDo: Use Excel Package
    public function importCSV(ImportCSVRequest $request)
    {
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
                            'updated_at' => $now,
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
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 400);
        }
    }
}
