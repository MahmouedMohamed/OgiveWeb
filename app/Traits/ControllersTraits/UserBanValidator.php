<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\UserBanNotFound;
use App\Models\UserBan;
use App\Traits\ValidatorLanguagesSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait UserBanValidator
{
    use ValidatorLanguagesSupport;

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
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
