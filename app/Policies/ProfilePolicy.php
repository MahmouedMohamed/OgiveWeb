<?php

namespace App\Policies;

use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\Profile;
use App\Models\User;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return mixed
     */
    public function view(User $user, Profile $profile)
    {
        return ($user->profile == $profile->id ||
        $this->hasAbility($user, AvailableAbilities::ViewUserProfile))
            && $this->hasNoBan($user, BanTypes::ViewUserProfile);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return mixed
     */
    public function update(User $user, Profile $profile)
    {
        return ($user->profile == $profile->id ||
        $this->hasAbility($user, AvailableAbilities::UpdateUserProfile))
            && $this->hasNoBan($user, BanTypes::UpdateUserProfile);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, Profile $profile)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return mixed
     */
    public function restore(User $user, Profile $profile)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Profile $profile)
    {
        //
    }
}
