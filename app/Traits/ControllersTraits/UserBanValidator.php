<?php

namespace App\Traits\ControllersTraits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait UserBanValidator
{
    public function validateUserBan(Request $request)
    {
        $rules = [
            'tag' => 'required',
            'start_at' => 'date',
            'end_at' => 'date|after:from',
        ];

        return Validator::make($request->all(), $rules);
    }
}
