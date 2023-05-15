<?php

namespace App\Policies;

use App\Models\Ahed\OfflineTransaction;
use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\User;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfflineTransactionPolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can collect transaction.
     *
     * @return mixed
     */
    public function collect(User $user)
    {
        return $this->hasAbility($user, AvailableAbilities::CollectOfflineTransaction)
            && $this->hasNoBan($user, BanTypes::CollectOfflineTransaction);
    }

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
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function view(User $user, OfflineTransaction $offlineTransaction)
    {
        return ($this->hasAbility($user, AvailableAbilities::ViewOfflineTransaction)
            || $user->id == $offlineTransaction->giver)
            && $this->hasNoBan($user, BanTypes::ViewOfflineTransaction);
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
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function update(User $user, OfflineTransaction $offlineTransaction)
    {
        return ($this->hasAbility($user, AvailableAbilities::UpdateOfflineTransaction)
            || $user->id == $offlineTransaction->giver)
            && $this->hasNoBan($user, BanTypes::UpdateOfflineTransaction) &&
            $offlineTransaction->selectedDate == null &&
            ! ($offlineTransaction->collected);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function delete(User $user, OfflineTransaction $offlineTransaction)
    {
        return ($this->hasAbility($user, AvailableAbilities::DeleteOfflineTransaction)
            || $user->id == $offlineTransaction->giver)
            && $this->hasNoBan($user, BanTypes::DeleteOfflineTransaction) &&
            $offlineTransaction->selectedDate == null &&
            ! ($offlineTransaction->collected);
    }

    /**
     * Determine whether the user can restore the model.
     *
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
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return mixed
     */
    public function forceDelete(User $user, OfflineTransaction $offlineTransaction)
    {
        //
    }
}
