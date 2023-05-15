<?php

namespace App\Policies;

use App\Models\Ataa\FoodSharingMarker;
use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\BaseUserModel;
use App\Models\User;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodSharingMarkerPolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(BaseUserModel $user)
    {
        return $this->hasNoBan($user, BanTypes::ViewFoodSharingMarker);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function view(BaseUserModel $user, FoodSharingMarker $foodSharingMarker)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(BaseUserModel $user)
    {
        return $this->hasNoBan($user, BanTypes::CreateFoodSharingMarker);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function collect(BaseUserModel $user)
    {
        return $this->hasNoBan($user, BanTypes::CollectFoodSharingMarker);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function update(BaseUserModel $user, FoodSharingMarker $foodSharingMarker)
    {
        return ($this->hasAbility($user, AvailableAbilities::UpdateFoodSharingMarker)
            || $user->id == $foodSharingMarker->owner_id)
            && $this->hasNoBan($user, BanTypes::UpdateFoodSharingMarker);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function delete(BaseUserModel $user, FoodSharingMarker $foodSharingMarker)
    {
        return $user->id == $foodSharingMarker->owner_id || $this->hasAbility($user, AvailableAbilities::DeleteFoodSharingMarker)
            && $this->hasNoBan($user, BanTypes::DeleteFoodSharingMarker);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function restore(BaseUserModel $user, FoodSharingMarker $foodSharingMarker)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function forceDelete(BaseUserModel $user, FoodSharingMarker $foodSharingMarker)
    {
        //
    }
}
