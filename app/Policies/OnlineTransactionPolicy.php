<?php

namespace App\Policies;

use App\Models\OnlineTransaction;
use App\Models\User;
use App\Models\BanTypes;
use Illuminate\Auth\Access\HandlesAuthorization;

class OnlineTransactionPolicy
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
     * @param  \App\Models\OnlineTransaction  $transaction
     * @return mixed
     */
    public function view(User $user, OnlineTransaction $transaction)
    {
        //ToDo: isAdmin() -> Change
        return $user->id == $transaction->giver || $user->isAdmin();
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
     * @param  \App\Models\OnlineTransaction  $transaction
     * @return mixed
     */
    public function update(User $user, OnlineTransaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OnlineTransaction  $transaction
     * @return mixed
     */
    public function delete(User $user, OnlineTransaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OnlineTransaction  $transaction
     * @return mixed
     */
    public function restore(User $user, OnlineTransaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OnlineTransaction  $transaction
     * @return mixed
     */
    public function forceDelete(User $user, OnlineTransaction $transaction)
    {
        //
    }
}
