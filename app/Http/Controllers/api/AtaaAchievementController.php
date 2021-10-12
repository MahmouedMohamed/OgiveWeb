<?php

namespace App\Http\Controllers\api;

use App\Exceptions\AtaaAchievementNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\AtaaAchievement;
use App\Models\AtaaPrize;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHandler;
use App\Traits\ControllersTraits\AtaaAchievementValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Support\Facades\DB;

class AtaaAchievementController extends BaseController
{
    use UserValidator, AtaaAchievementValidator;
    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $userId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, String $userId)
    {
        try {
            $responseHandler = new ResponseHandler($request['language']);
            //Check User exists
            $user = $this->userExists($userId);
            $requesterUser = $this->userExists($request['requesterId']);
            //if Ataa Achievement is null -> Create one with this user to return a results
            $this->userIsAuthorized($requesterUser, 'view', $user->ataaAchievement ?? ((new AtaaAchievement())->user()->associate($user)));

            $markersCollected = $user->ataaAchievement->markers_collected ?? 0;
            $markersPosted = $user->ataaAchievement->markers_posted ?? 0;

            //Latest can replace that?? Cause of events
            $ataaPrizesAcquiredByUser = DB::table('ataa_prizes')->leftJoin(
                'user_ataa_acquired_prizes',
                'ataa_prizes.id',
                '=',
                'user_ataa_acquired_prizes.prize_id'
            )->where('user_id', $user->id)->get() ?? 0;
            $highestPrizeAcquired = $ataaPrizesAcquiredByUser->sortBy('level', 1, true)->first()->level ?? 0;

            $ataaBadgesAcquiredByUser = DB::table('ataa_badges')->leftJoin(
                'user_ataa_acquired_badges',
                'ataa_badges.id',
                '=',
                'user_ataa_acquired_badges.badge_id'
            )->where('user_id', $user->id)->latest('user_ataa_acquired_badges.created_at')->first();
            $latestBadgeAcquired = $ataaBadgesAcquiredByUser->name ?? null;

            $response = [
                'markers_collected' => $markersCollected,
                'markers_posted' => $markersPosted,
                'current_level' => $highestPrizeAcquired,
                'latest_badge' => $latestBadgeAcquired
            ];

            return $this->sendResponse($response, 'User Achievement Returned Successfully');
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (AtaaAchievementNotFound $e) {
            return $this->sendError($responseHandler->words['AchievementNotFound']);
        } catch (UserNotAuthorized $e) {
            if ($user->ataaAchievement)
                $e->report($requesterUser, 'AccessAtaaAchievement', $user->ataaAchievement);
            return $this->sendForbidden($responseHandler->words['ShowAchievementForbidden']);
        }
    }
}
