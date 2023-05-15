<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\AtaaBadgeNotFound;
use App\Models\Ataa\AtaaBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait AtaaBadgeValidator
{
    /**
     * Returns If Online Transaction exists or not.
     *
     * @return mixed
     */
    public function badgeExists(string $id)
    {
        $badge = AtaaBadge::find($id);
        if (! $badge) {
            throw new AtaaBadgeNotFound();
        }

        return $badge;
    }

    public function validateBadge(Request $request)
    {
        $rules = [
            'userId' => 'required',
            'name' => 'required',
            'arabic_name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
        ];

        return Validator::make($request->all(), $rules);
    }
}
