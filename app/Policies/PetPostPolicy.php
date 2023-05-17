<?php

namespace App\Policies;

use App\Models\BreedMe\Pet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetPostPolicy
{
    use HandlesAuthorization;

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
     * @param  \App\Models\Pet  $pet
     * @return mixed
     */
    public function view(User $user, Pet $pet)
    {
        //
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
     * @param  \App\Models\Pet  $pet
     * @return mixed
     */
    public function update(User $user, Pet $pet)
    {
        //
        return $user->id === $pet->user_id;

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Pet  $pet
     * @return mixed
     */
    public function delete(User $user, Pet $pet)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Pet  $pet
     * @return mixed
     */
    public function restore(User $user, Pet $pet)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Pet  $pet
     * @return mixed
     */
    public function forceDelete(User $user, Pet $pet)
    {
        //
    }
}
