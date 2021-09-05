<?php

namespace App\Policies;

use App\Models\AtaaAchievement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtaaAchievementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function view(User $user, AtaaAchievement $ataaAchievement)
    {
        return $user->isAdmin() || $user == $ataaAchievement->user;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function update(User $user, AtaaAchievement $ataaAchievement)
    {
        //
    }

    /**
     * Determine whether the user can freeze the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function freeze(User $user, AtaaAchievement $ataaAchievement)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can defreeze the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function defreeze(User $user, AtaaAchievement $ataaAchievement)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function delete(User $user, AtaaAchievement $ataaAchievement)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaAchievement  $ataaAchievement
     * @return mixed
     */
    public function forceDelete(User $user, AtaaAchievement $ataaAchievement)
    {
        //
    }
}
