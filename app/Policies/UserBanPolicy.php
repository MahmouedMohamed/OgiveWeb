<?php

namespace App\Policies;

use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\User;
use App\Models\UserBan;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserBanPolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
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
     * @return mixed
     */
    public function view(User $user, UserBan $userBan)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
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
     * @return mixed
     */
    public function restore(User $user, UserBan $userBan)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return mixed
     */
    public function forceDelete(User $user, UserBan $userBan)
    {
        //
    }
}
