<?php

namespace App\Policies;

use App\Models\AtaaPrize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtaaPrizePolicy
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
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return mixed
     */
    public function view(User $user, AtaaPrize $ataaPrize)
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
        //ToDo: Make sure that the admin has the privilage to create a prize
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return mixed
     */
    public function update(User $user, AtaaPrize $ataaPrize)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return mixed
     */
    public function delete(User $user, AtaaPrize $ataaPrize)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return mixed
     */
    public function restore(User $user, AtaaPrize $ataaPrize)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return mixed
     */
    public function forceDelete(User $user, AtaaPrize $ataaPrize)
    {
        //
    }
}
