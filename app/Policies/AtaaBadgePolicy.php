<?php

namespace App\Policies;

use App\Models\Ataa\AtaaBadge;
use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\User;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtaaBadgePolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->hasNoBan($user, BanTypes::ViewAtaaBadge);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AtaaBadge $ataaBadge)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->hasAbility($user, AvailableAbilities::CreateAtaaBadge)
            && $this->hasNoBan($user, BanTypes::CreateAtaaBadge);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AtaaBadge $ataaBadge)
    {
        return $this->hasAbility($user, AvailableAbilities::UpdateAtaaBadge)
            && $this->hasNoBan($user, BanTypes::UpdateAtaaBadge);
    }

    /**
     * Determine whether the user can activate the model.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return mixed
     */
    public function activate(User $user, AtaaBadge $ataaBadge)
    {
        return $this->hasAbility($user, AvailableAbilities::ActivateAtaaBadge)
            && $this->hasNoBan($user, BanTypes::ActivateAtaaBadge);
    }

    /**
     * Determine whether the user can activate the model.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return mixed
     */
    public function deactivate(User $user, AtaaBadge $ataaBadge)
    {
        return $this->hasAbility($user, AvailableAbilities::DeactivateAtaaBadge)
            && $this->hasNoBan($user, BanTypes::DeactivateAtaaBadge);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AtaaBadge $ataaBadge)
    {
        return $this->hasAbility($user, AvailableAbilities::DeleteAtaaBadge)
            && $this->hasNoBan($user, BanTypes::DeleteAtaaBadge);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AtaaBadge $ataaBadge)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AtaaBadge $ataaBadge)
    {
        return false;
    }
}
