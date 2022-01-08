<?php

namespace App\Policies;

use App\Models\Ataa\FoodSharingMarker;
use App\Models\User;
use App\Models\BanTypes;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AvailableAbilities;
use App\Traits\HasNoBan;
use App\Traits\HasAbility;

class FoodSharingMarkerPolicy
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
        return $this->hasNoBan($user, BanTypes::ViewFoodSharingMarker);
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
        return $this->hasNoBan($user, BanTypes::CreateFoodSharingMarker);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function collect(User $user)
    {
        return $this->hasNoBan($user, BanTypes::CollectFoodSharingMarker);
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
        return ($this->hasAbility($user, AvailableAbilities::UpdateFoodSharingMarker)
            || $user == $foodSharingMarker->user)
            && $this->hasNoBan($user, BanTypes::UpdateFoodSharingMarker);
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
        return $user == $foodSharingMarker->user || $this->hasAbility($user, AvailableAbilities::DeleteFoodSharingMarker)
            && $this->hasNoBan($user, BanTypes::DeleteFoodSharingMarker);
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
