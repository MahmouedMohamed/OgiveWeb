<?php

namespace App\Policies;

use App\Models\Ataa\FoodSharingMarker;
use App\Models\User;
use App\Models\BanTypes;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AvailableAbilities;
use App\Models\BaseUserModel;
use App\Traits\HasNoBan;
use App\Traits\HasAbility;

class FoodSharingMarkerPolicy
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
        return $this->hasNoBan($user, BanTypes::ViewFoodSharingMarker);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\BaseUserModel  $user
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
     * @param  \App\Models\BaseUserModel  $user
     * @return mixed
     */
    public function create(BaseUserModel $user)
    {
        return $this->hasNoBan($user, BanTypes::CreateFoodSharingMarker);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @return mixed
     */
    public function collect(BaseUserModel $user)
    {
        return $this->hasNoBan($user, BanTypes::CollectFoodSharingMarker);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function update(BaseUserModel $user, FoodSharingMarker $foodSharingMarker)
    {
        // dd($user->id);
        // dd($foodSharingMarker->user->id);
        // dd(($this->hasAbility($user, AvailableAbilities::UpdateFoodSharingMarker)
        // || $user == $foodSharingMarker->user));
        return ($this->hasAbility($user, AvailableAbilities::UpdateFoodSharingMarker)
            || $user->id == $foodSharingMarker->owner_id)
            && $this->hasNoBan($user, BanTypes::UpdateFoodSharingMarker);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\BaseUserModel  $user
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
     * @param  \App\Models\BaseUserModel  $user
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
     * @param  \App\Models\BaseUserModel  $user
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return mixed
     */
    public function forceDelete(BaseUserModel $user, FoodSharingMarker $foodSharingMarker)
    {
        //
    }
}
