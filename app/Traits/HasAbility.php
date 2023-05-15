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
     * @param  string  $banType
     * @return mixed
     */
    public function hasAbility(BaseUserModel $user, string $ability)
    {
        return $user->abilities()->contains($ability);
    }
}
