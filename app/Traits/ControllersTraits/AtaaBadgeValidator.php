<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\AtaaBadgeNotFound;
use App\Models\Ataa\AtaaBadge;
use App\Traits\ValidatorLanguagesSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait AtaaBadgeValidator
{
    use ValidatorLanguagesSupport;

    /**
     * Returns If Online Transaction exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function badgeExists(String $id)
    {
        $badge = AtaaBadge::find($id);
        if (!$badge)
            throw new AtaaBadgeNotFound();
        return $badge;
    }

    public function validateBadge(Request $request)
    {
        $rules = [
            'userId' => 'required',
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string'
        ];
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
