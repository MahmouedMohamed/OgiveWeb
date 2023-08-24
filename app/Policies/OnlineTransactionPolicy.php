<?php

namespace App\Policies;

use App\Models\Ahed\OnlineTransaction;
use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\User;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class OnlineTransactionPolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

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
     * @param  \App\Models\OnlineTransaction  $transaction
     * @return mixed
     */
    public function view(User $user, OnlineTransaction $transaction)
    {
        return ($this->hasAbility($user, AvailableAbilities::ViewOnlineTransaction)
            || $user->id == $transaction->giver_id)
            && $this->hasNoBan($user, BanTypes::ViewOnlineTransaction);
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
     * @param  \App\Models\OnlineTransaction  $transaction
     * @return mixed
     */
    public function forceDelete(User $user, OnlineTransaction $transaction)
    {
        //
    }
}
