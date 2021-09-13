<?php

namespace App\Policies;

use App\Models\AtaaAchievement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\BanType;
use App\Models\AvailableAbilities;

class AtaaAchievementPolicy
{
    use HandlesAuthorization;

    private $banType;

    public function __construct()
    {
        $this->banType = new BanType();
    }

    /**
     * Returns If User has that kind of ban or not.
     *
     * @param  \App\Models\User  $user
     * @param  String  $banType
     * @return mixed
     */
    public function hasNoBan(User $user, String $banType)
    {
        return $user->bans()->where('active', '=', 1)->where('tag', '=', $this->banType->types[$banType])->get()->first() == null;
    }

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
        return $user->abilities()->contains(AvailableAbilities::ViewAtaaAchievement) && $this->hasNoBan($user, 'ViewAtaaAchievement')
            || $user == $ataaAchievement->user;
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
        return $user->abilities()->contains(AvailableAbilities::FreezeAtaaAchievement) && $this->hasNoBan($user, 'FreezeAtaaAchievement');
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
        return $user->abilities()->contains(AvailableAbilities::FreezeAtaaAchievement) && $this->hasNoBan($user, 'FreezeAtaaAchievement');
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
