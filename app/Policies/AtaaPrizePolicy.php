<?php

namespace App\Policies;

use App\Models\AtaaPrize;
use App\Models\AvailableAbilities;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\BanType;

class AtaaPrizePolicy
{
    use HandlesAuthorization;

    private $banType;

    public function __construct()
    {
        $this->banType = new BanType();
    }

    /**
     * Returns If User has that kind of ban or not.
     *
     * @param  \App\Models\User  $user
     * @param  String  $banType
     * @return mixed
     */
    public function hasNoBan(User $user, String $banType)
    {
        return $user->bans()->where('active', '=', 1)->where('tag', '=', $this->banType->types[$banType])->get()->first() == null;
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->abilities()->contains(AvailableAbilities::ViewAtaaPrize) && $this->hasNoBan($user, 'ViewAtaaPrize');
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
        return $user->abilities()->contains(AvailableAbilities::CreateAtaaPrize) && $this->hasNoBan($user, 'CreateAtaaPrize');
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
        return $user->abilities()->contains(AvailableAbilities::UpdateAtaaPrize) && $this->hasNoBan($user, 'UpdateAtaaPrize');
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
        return $user->abilities()->contains(AvailableAbilities::DeleteAtaaPrize) && $this->hasNoBan($user, 'DeleteAtaaPrize');
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
