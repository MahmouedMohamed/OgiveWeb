<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserBan;
use App\Models\BanTypes;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AvailableAbilities;
use App\Traits\HasNoBan;
use App\Traits\HasAbility;

class UserBanPolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {

        return $this->hasAbility($user, AvailableAbilities::ViewUserBan)
            && $this->hasNoBan($user, BanTypes::ViewUserBan);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserBan  $userBan
     * @return mixed
     */
    public function view(User $user, UserBan $userBan)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, User $bannedUser)
    {
        return $user != $bannedUser &&
            $this->hasAbility($user, AvailableAbilities::CreateUserBan) &&
            $this->hasNoBan($user, BanTypes::CreateUserBan);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserBan  $userBan
     * @return mixed
     */
    public function update(User $user, UserBan $userBan)
    {
        return $this->hasAbility($user, AvailableAbilities::UpdateUserBan) &&
            $this->hasNoBan($user, BanTypes::UpdateUserBan);
    }

    /**
     * Determine whether the user can activate the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserBan  $userBan
     * @return mixed
     */
    public function activate(User $user, UserBan $userBan)
    {
        return $this->hasAbility($user, AvailableAbilities::ActivateUser) &&
            $this->hasNoBan($user, BanTypes::ActivateUser);
    }

    /**
     * Determine whether the user can deactivate the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserBan  $userBan
     * @return mixed
     */
    public function deactivate(User $user, UserBan $userBan)
    {
        return $this->hasAbility($user, AvailableAbilities::DeactivateUser) &&
            $this->hasNoBan($user, BanTypes::DeactivateUser);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserBan  $userBan
     * @return mixed
     */
    public function delete(User $user, UserBan $userBan)
    {
        return $this->hasAbility($user, AvailableAbilities::DeleteUserBan) &&
            $this->hasNoBan($user, BanTypes::DeleteUserBan);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserBan  $userBan
     * @return mixed
     */
    public function restore(User $user, UserBan $userBan)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserBan  $userBan
     * @return mixed
     */
    public function forceDelete(User $user, UserBan $userBan)
    {
        //
    }
}
