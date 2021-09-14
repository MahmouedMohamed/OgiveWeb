<?php

namespace App\Policies;

use App\Models\FoodSharingMarker;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\BanType;

class FoodSharingMarkerPolicy
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
        return $this->hasNoBan($user,'ViewFoodSharingMarker');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function view(User $user, FoodSharingMarker $foodSharingMarker)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->hasNoBan($user,'CreateFoodSharingMarker');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function collect(User $user)
    {
        return $this->hasNoBan($user,'CollectFoodSharingMarker');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function update(User $user, FoodSharingMarker $foodSharingMarker)
    {
        return $user->isAdmin() || $user == $foodSharingMarker->user;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function delete(User $user, FoodSharingMarker $foodSharingMarker)
    {
        return $user->isAdmin() || $user == $foodSharingMarker->user;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function restore(User $user, FoodSharingMarker $foodSharingMarker)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function forceDelete(User $user, FoodSharingMarker $foodSharingMarker)
    {
        //
    }
}
