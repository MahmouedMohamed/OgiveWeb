<?php

namespace App\Traits;
use App\Models\User;
use App\Models\BanType;
use App\Models\AvailableAbilities;

trait HasAbility {

    /**
     * Returns If User has that kind of ability or not.
     *
     * @param  \App\Models\User  $user
     * @param  String  $banType
     * @return mixed
     */
    public function hasAbility(User $user, String $ability)
    {
        return $user->abilities()->contains($ability);
    }

}
