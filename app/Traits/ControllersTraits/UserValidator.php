<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Models\BaseUserModel;
use App\Models\User;

trait UserValidator
{
    /**
     * Returns If User exists or not.
     *
     * @param  string  $id
     * @return mixed
     */
    public function userExists($id)
    {
        $user = User::find($id);
        if (! $user) {
            throw new UserNotFound();
        }

        return $user;
    }

    /**
     * Returns If User Can make action or not.
     *
     * @param  User  $user
     * @param  string  $id
     * @param  mixed  $model
     * @return mixed
     */
    public function userIsAuthorized(BaseUserModel $user, string $action, $model)
    {
        if (! $user->can($action, $model)) {
            throw new UserNotAuthorized();
        }

    }
}
