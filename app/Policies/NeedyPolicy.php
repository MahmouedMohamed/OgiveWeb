<?php

namespace App\Policies;

use App\Models\Needy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\BanType;

class NeedyPolicy
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
     * Determine whether the user can approve.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function approve(User $user)
    {
        return $user->isAdmin() && $this->hasNoBan($user, 'ApproveNeedy');
    }
    /**
     * Determine whether the user can disapprove.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function disapprove(User $user)
    {
        return $user->isAdmin() && $this->hasNoBan($user, 'DisapproveNeedy');
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
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->hasNoBan($user, 'CreateNeedy');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Needy  $needy
     * @return mixed
     */
    public function update(User $user, Needy $needy)
    {
        return ($user->id == $needy->createdBy || $user->isAdmin()) &&
            $this->hasNoBan($user, 'UpdateNeedy');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Needy  $needy
     * @return mixed
     */
    public function delete(User $user, Needy $needy)
    {
        return ($user->id == $needy->createdBy || $user->isAdmin()) &&
            $this->hasNoBan($user, 'DeleteNeedy');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Needy  $needy
     * @return mixed
     */
    public function forceDelete(User $user, Needy $needy)
    {
        //
    }
}
