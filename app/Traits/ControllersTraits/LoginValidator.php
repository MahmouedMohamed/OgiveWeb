<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\LoginParametersNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Models\BanTypes;
use App\Models\BaseUserModel;
use App\Models\User;
use Illuminate\Http\Request;

trait LoginValidator
{

    /**
     * Returns If FoodSharingMarker exists or not.
     *
     * @param Request $request
     * @return mixed
     */
    public function validateLoginParameters(Request $request)
    {
        if (!$request['accessType'])
            throw new LoginParametersNotFound("Access Type");
        if (!$request['appType'])
            throw new LoginParametersNotFound("App Type");
        if (!$request['email'])
            throw new LoginParametersNotFound("Email");
        if (!$request['password'])
            throw new LoginParametersNotFound("Password");
        if ($request['appType'] == 'TimeCatcher' && !$request['fcmToken'])
            throw new LoginParametersNotFound('FCM Token');
    }

    public function userBanValidator(BaseUserModel $user)
    {
        $loginBan = $user->bans()->where('active', '=', 1)->where('tag', '=', BanTypes::Login)->get()->first();
        if ($loginBan)
            throw new UserNotAuthorized("Sorry, but is seems you are banned from login until " . ($loginBan['end_at'] ?? "infinite period of time."));
    }
}
