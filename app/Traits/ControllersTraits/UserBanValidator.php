<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\UserBanNotFound;
use App\Models\UserBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait UserBanValidator
{

    /**
     * Returns If Online Transaction exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function userBanExists(String $id)
    {
        $userBan = UserBan::find($id);
        if (!$userBan)
            throw new UserBanNotFound();
        return $userBan;
    }

    public function validateUserBan(Request $request)
    {
        $rules = [
            'tag' => 'required',
            'start_at' => 'date',
            'end_at' => 'date|after:from'
        ];
        return Validator::make($request->all(), $rules);
    }
}
