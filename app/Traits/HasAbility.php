<?php

namespace App\Traits;

use App\Models\BaseUserModel;
use App\Models\User;

trait HasAbility
{

    /**
     * Returns If User has that kind of ability or not.
     *
     * @param  \App\Models\User  $user
     * @param  String  $banType
     * @return mixed
     */
    public function hasAbility(BaseUserModel $user, String $ability)
    {dd();
        return $user->abilities()->contains($ability);
    }
}
