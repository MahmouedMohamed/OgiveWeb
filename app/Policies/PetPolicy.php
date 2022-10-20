<?php

namespace App\Policies;

use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\BreedMe\Pet;
use App\Models\User;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetPolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->hasNoBan($user, BanTypes::ViewPet);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BreedMe\Pet  $pet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Pet $pet)
    {
        return $this->hasNoBan($user, BanTypes::ViewPet);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BreedMe\Pet  $pet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->hasNoBan($user, BanTypes::CreatePet);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BreedMe\Pet  $pet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Pet $pet)
    {
        return ($user->id == $pet->createdBy ||
            $this->hasAbility($user, AvailableAbilities::UpdatePet)) &&
            $this->hasNoBan($user, BanTypes::UpdatePet);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Pet $pet)
    {
        return ($user->id == $pet->createdBy ||
            $this->hasAbility($user, AvailableAbilities::DeletePet)) &&
            $this->hasNoBan($user, BanTypes::DeletePet);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BreedMe\Pet  $pet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Pet $pet)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BreedMe\Pet  $pet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Pet $pet)
    {
        return false;
    }
}
