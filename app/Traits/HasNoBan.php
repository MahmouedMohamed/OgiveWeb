<?php

namespace App\Traits;

use App\Models\User;

trait HasNoBan
{

    /**
     * Returns If User has that kind of ban or not.
     *
     * @param  \App\Models\User  $user
     * @param  String  $banType
     * @return mixed
     */
    public function hasNoBan(User $user, String $banType)
    {
        return $user->bans()->where('active', '=', 1)
            ->where('tag', '=', $banType)
            ->get()->first() == null;
    }
}
