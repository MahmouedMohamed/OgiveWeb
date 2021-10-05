<?php

namespace App\Traits;

use App\Models\User;
use App\Exceptions\UserNotFound;

trait UserChecker
{

    /**
     * Returns If User has that kind of ability or not.
     *
     * @param  \App\Models\User  $user
     * @param  String  $banType
     * @return mixed
     */
    public function userExists(String $id)
    {
        $user = User::find($id);
        if (!$user)
            throw new UserNotFound();
        return $user;
    }
}
