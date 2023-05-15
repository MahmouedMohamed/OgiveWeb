<?php

namespace App\Traits;

use App\Models\BaseUserModel;
use App\Models\User;

trait HasNoBan
{
    /**
     * Returns If User has that kind of ban or not.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function hasNoBan(BaseUserModel $user, string $banType)
    {
        return $user->bans()->where('active', '=', 1)
            ->where('tag', '=', $banType)
            ->get()->first() == null;
    }
}
