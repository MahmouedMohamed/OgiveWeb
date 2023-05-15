<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\UserNotAuthorized;
use App\Models\BanTypes;
use App\Models\BaseUserModel;

trait LoginValidator
{
    public function userBanValidator(BaseUserModel $user)
    {
        $loginBan = $user->bans()->where('active', '=', 1)->where('tag', '=', BanTypes::Login)->get()->first();
        if ($loginBan) {
            throw new UserNotAuthorized('Sorry, but is seems you are banned from login until '.($loginBan['end_at'] ?? 'infinite period of time.'));
        }
    }
}
