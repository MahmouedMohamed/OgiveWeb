<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\AtaaAchievement;
use App\Models\AtaaPrize;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHandler;
use Illuminate\Support\Facades\DB;

class AtaaAchievementController extends BaseController
{

    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $userId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, String $userId)
    {
        $responseHandler = new ResponseHandler($request['language']);
        //Check User exists
        $user = User::find($userId);
        if ($user == null) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        }
        $requesterUser = User::find($request['requesterId']);
        if ($requesterUser == null) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        }

        //if not found
        if (!$user->ataaAchievement) {
            return $this->sendError($responseHandler->words['AchievementNotFound']);
        }
        // dd($user->ataaAchievement->user);
        if (!$requesterUser->can('view', $user->ataaAchievement)) {
            return $this->sendForbidden($responseHandler->words['ShowAchievementForbidden']);
        }

        $ataaPrizesAcquiredByUser = DB::table('ataa_prizes')->leftJoin(
            'user_ataa_acquired_prizes',
            'ataa_prizes.id',
            '=',
            'user_ataa_acquired_prizes.prize_id'
        )->where('user_id', $user->id)->get();

        return $ataaPrizesAcquiredByUser;
    }
}
