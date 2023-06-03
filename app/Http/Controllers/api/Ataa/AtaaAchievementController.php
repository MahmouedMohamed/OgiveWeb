<?php

namespace App\Http\Controllers\api\Ataa;

use App\Exceptions\UserNotAuthorized;
use App\Http\Controllers\api\BaseController;
use App\Models\Ataa\AtaaAchievement;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtaaAchievementController extends BaseController
{
    use UserValidator;

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {
            //Check User exists
            $user = $request->user;
            //if Ataa Achievement is null -> Create one with this user to return a results
            $this->userIsAuthorized($user, 'view', $user->ataaAchievement ?? ((new AtaaAchievement())->user()->associate($user)));

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
                'latest_badge' => $latestBadgeAcquired,
            ];

            return $this->sendResponse($response, __('General.DataRetrievedSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            if ($user->ataaAchievement) {
                $e->report($request->user, 'AccessAtaaAchievement', $user->ataaAchievement);
            }

            return $this->sendForbidden(__('Ataa.ShowAchievementForbidden'));
        }
    }
}
