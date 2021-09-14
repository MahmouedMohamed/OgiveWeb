<?php

namespace App\Traits;
use App\Models\User;
use App\Models\BanType;

trait HasNoBan {

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

}
