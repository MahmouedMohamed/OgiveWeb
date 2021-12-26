<?php

namespace App\Policies;

use App\Models\AvailableAbilities;
use App\Models\BanTypes;
use App\Models\Like;
use App\Models\User;
use App\Traits\HasAbility;
use App\Traits\HasNoBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class LikePolicy
{
    use HandlesAuthorization, HasNoBan, HasAbility;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->hasNoBan($user, BanTypes::ViewLike);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Like $like)
    {
        return $this->hasNoBan($user, BanTypes::ViewLike);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->hasNoBan($user, BanTypes::CreateMemory);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Like $like)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Like $like)
    {
        return ($user->id == $like->userId ||
            $this->hasAbility($user, AvailableAbilities::DeleteLike)) &&
            $this->hasNoBan($user, BanTypes::DeleteLike);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Like $like)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Like $like)
    {
        //
    }
}