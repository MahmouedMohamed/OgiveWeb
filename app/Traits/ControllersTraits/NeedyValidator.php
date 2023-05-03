<?php

namespace App\Traits\ControllersTraits;

use App\Models\Ahed\Needy;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\NeedyNotApproved;
use App\Exceptions\NeedyIsSatisfied;
use App\Exceptions\NeedyMediaNotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait NeedyValidator
{

    /**
     * Returns If Needy exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function needyExists(String $id)
    {
        $needy = Needy::find($id);
        if (!$needy)
            throw new NeedyNotFound();
        return $needy;
    }

    public function needySelfLock(String $id)
    {
        $needy = Needy::lockForUpdate()->find($id);
        if (!$needy)
            throw new NeedyNotFound();
        return $needy;
    }
    /**
     * Returns If Needy approved or not.
     *
     * @param Needy $needy
     * @return mixed
     */
    public function needyApproved(Needy $needy)
    {
        if (!$needy->approved)
            throw new NeedyNotApproved();
        return;
    }
    /**
     * Returns If Needy satisfied or not.
     *
     * @param Needy $needy
     * @return mixed
     */
    public function needyIsSatisfied(Needy $needy)
    {
        if ($needy->satisfied)
            throw new NeedyIsSatisfied();
        return;
    }

    public function needyMediaExists(Needy $needy, $id)
    {
        $needyMedia = $needy->medias()->whereIn('id', [$id])->first();
        if (!$needyMedia)
            throw new NeedyMediaNotFound();
        return $needyMedia;
    }

    public function validateNeedy(Request $request, String $related)
    {
        switch ($related) {
            case 'addImage':
                $rules = [
                    'images' => 'required',
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'before' => 'required|boolean',
                ];
                break;
        }
        return Validator::make($request->all(), $rules);
    }
}
