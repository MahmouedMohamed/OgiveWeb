<?php

namespace App\Policies;

use App\Models\Ahed\Needy;
use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\User;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class NeedyPolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can approve.
     *
     * @return mixed
     */
    public function approve(User $user)
    {
        return $this->hasAbility($user, AvailableAbilities::ApproveNeedy)
            && $this->hasNoBan($user, BanTypes::ApproveNeedy);
    }

    /**
     * Determine whether the user can disapprove.
     *
     * @return mixed
     */
    public function disapprove(User $user)
    {
        return $this->hasAbility($user, AvailableAbilities::DisapproveNeedy)
            && $this->hasNoBan($user, BanTypes::DisapproveNeedy);
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
     * @param  \App\Models\Needy  $needy
     * @return mixed
     */
    public function view(User $user, Needy $needy)
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
        return $this->hasNoBan($user, BanTypes::CreateNeedy);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Needy  $needy
     * @return mixed
     */
    public function update(User $user, Needy $needy)
    {
        return ($user->id == $needy->createdBy ||
            $this->hasAbility($user, AvailableAbilities::UpdateNeedy)) &&
            $this->hasNoBan($user, BanTypes::UpdateNeedy);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Needy  $needy
     * @return mixed
     */
    public function delete(User $user, Needy $needy)
    {
        return ($user->id == $needy->createdBy ||
            $this->hasAbility($user, AvailableAbilities::DeleteNeedy)) &&
            $this->hasNoBan($user, BanTypes::DeleteNeedy);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Needy  $needy
     * @return mixed
     */
    public function restore(User $user, Needy $needy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Needy  $needy
     * @return mixed
     */
    public function forceDelete(User $user, Needy $needy)
    {
        //
    }
}
