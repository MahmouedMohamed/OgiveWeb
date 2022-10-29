<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\UserNotAuthorized;
use App\Models\User;
use App\Exceptions\UserNotFound;
use App\Models\BaseUserModel;

trait UserValidator
{

    /**
     * Returns If User exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function userExists($id)
    {
        $user = User::find($id);
        if (!$user)
            throw new UserNotFound();
        return $user;
    }

    /**
     * Returns If User Can make action or not.
     *
     * @param User $user
     * @param String $id
     * @param mixed $model
     *
     * @return mixed
     */
    public function userIsAuthorized(BaseUserModel $user, String $action, $model)
    {
        if (!$user->can($action, $model))
            throw new UserNotAuthorized();
        return;
    }
}
