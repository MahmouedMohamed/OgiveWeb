<?php

namespace App\Policies;

use App\Models\Ataa\AtaaPrize;
use App\Models\AvailableAbilities;
use App\Models\User;
use App\Models\BanTypes;
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
        return $this->hasNoBan($user, BanTypes::ViewAtaaPrize);
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
        return $this->hasAbility($user, AvailableAbilities::CreateAtaaPrize)
            && $this->hasNoBan($user, BanTypes::CreateAtaaPrize);
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
        return $this->hasAbility($user, AvailableAbilities::UpdateAtaaPrize)
            && $this->hasNoBan($user, BanTypes::UpdateAtaaPrize);
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
        return $this->hasAbility($user, AvailableAbilities::ActivateAtaaPrize)
            && $this->hasNoBan($user, BanTypes::ActivateAtaaPrize);
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
        return $this->hasAbility($user, AvailableAbilities::DeactivateAtaaPrize)
            && $this->hasNoBan($user, BanTypes::DeactivateAtaaPrize);
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
        return $this->hasAbility($user, AvailableAbilities::DeleteAtaaPrize)
            && $this->hasNoBan($user, BanTypes::DeleteAtaaPrize);
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
