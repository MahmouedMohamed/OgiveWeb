<?php

namespace App\Policies;

use App\Models\Ataa\AtaaAchievement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\BaseUserModel;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;

class AtaaAchievementPolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @return mixed
     */
    public function viewAny(BaseUserModel $user)
    {
        return (($this->hasAbility($user, AvailableAbilities::ViewAtaaAchievement)
            && $this->hasNoBan($user, BanTypes::ViewAtaaAchievement)));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function view(BaseUserModel $user, AtaaAchievement $ataaAchievement)
    {
        return (($user->id == $ataaAchievement->owner_id) || ($this->hasAbility($user, AvailableAbilities::ViewAtaaAchievement)
            && $this->hasNoBan($user, BanTypes::ViewAtaaAchievement)));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @return mixed
     */
    public function create(BaseUserModel $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function update(BaseUserModel $user, AtaaAchievement $ataaAchievement)
    {
        //
    }

    /**
     * Determine whether the user can freeze the model.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function freeze(BaseUserModel $user, AtaaAchievement $ataaAchievement)
    {
        return $this->hasAbility($user, AvailableAbilities::FreezeAtaaAchievement)
            && $this->hasNoBan($user, BanTypes::FreezeAtaaAchievement);
    }

    /**
     * Determine whether the user can defreeze the model.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function defreeze(BaseUserModel $user, AtaaAchievement $ataaAchievement)
    {
        return $this->hasAbility($user, AvailableAbilities::DefreezeAtaaAchievement)
            && $this->hasNoBan($user, BanTypes::DefreezeAtaaAchievement);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function delete(BaseUserModel $user, AtaaAchievement $ataaAchievement)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function restore(User $user, AtaaAchievement $ataaAchievement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function forceDelete(User $user, AtaaAchievement $ataaAchievement)
    {
        //
    }
}
