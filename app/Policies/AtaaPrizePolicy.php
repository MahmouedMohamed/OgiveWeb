<?php

namespace App\Policies;

use App\Models\AtaaPrize;
use App\Models\AvailableAbilities;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasNoBan;
use App\Traits\HasAbility;

class AtaaPrizePolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->hasAbility($user, AvailableAbilities::ViewAtaaPrize) && $this->hasNoBan($user, 'ViewAtaaPrize');
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
        return $this->hasAbility($user, AvailableAbilities::CreateAtaaPrize) && $this->hasNoBan($user, 'CreateAtaaPrize');
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
        return $this->hasAbility($user, AvailableAbilities::UpdateAtaaPrize) && $this->hasNoBan($user, 'UpdateAtaaPrize');
    }

    /**
     * Determine whether the user can activate the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return mixed
     */
    public function activate(User $user, AtaaPrize $ataaPrize)
    {
        return $this->hasAbility($user, AvailableAbilities::ActivateAtaaPrize) && $this->hasNoBan($user, 'ActivateAtaaPrize');
    }

    /**
     * Determine whether the user can activate the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return mixed
     */
    public function deactivate(User $user, AtaaPrize $ataaPrize)
    {
        return $this->hasAbility($user, AvailableAbilities::DeactivateAtaaPrize) && $this->hasNoBan($user, 'DeactivateAtaaPrize');
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
        return $this->hasAbility($user, AvailableAbilities::DeleteAtaaPrize) && $this->hasNoBan($user, 'DeleteAtaaPrize');
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
