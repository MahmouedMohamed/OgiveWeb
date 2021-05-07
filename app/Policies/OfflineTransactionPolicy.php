<?php

namespace App\Policies;

use App\Models\OfflineTransaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfflineTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can collect transaction.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function collect(User $user){
        return $user->isAdmin();
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
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function view(User $user, OfflineTransaction $offlineTransaction)
    {
        return $user->id == $offlineTransaction->giver || $user->isAdmin();
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
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function update(User $user, OfflineTransaction $offlineTransaction)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function delete(User $user, OfflineTransaction $offlineTransaction)
    {
        return $user->id == $offlineTransaction->giver || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function restore(User $user, OfflineTransaction $offlineTransaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function forceDelete(User $user, OfflineTransaction $offlineTransaction)
    {
        //
    }
}
