<?php

namespace App\Traits\ControllersTraits;

use App\Models\Ahed\Needy;
use App\Models\Ahed\CaseType;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\NeedyNotApproved;
use App\Exceptions\NeedyIsSatisfied;
use App\Exceptions\NeedyMediaNotFound;
use App\Traits\ValidatorLanguagesSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait NeedyValidator
{
    use ValidatorLanguagesSupport;

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
        $rules = null;
        $caseType = new CaseType();
        switch ($related) {
            case 'store':
                $rules = [
                    'createdBy' => 'required',
                    'name' => 'required|max:255',
                    'age' => 'required|integer|max:100',
                    'severity' => 'required|integer|min:1|max:10',
                    'type' => 'required|in:' . $caseType->toString(),
                    'details' => 'required|max:1024',
                    'need' => 'required|numeric|min:1',
                    'address' => 'required',
                    'images' => 'required',
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];
                break;
            case 'update':
                $rules = [
                    'createdBy' => 'required',
                    'name' => 'required|max:255',
                    'age' => 'required|integer|max:100',
                    'severity' => 'required|integer|min:1|max:10',
                    'type' => 'required|in:' . $caseType->toString(),
                    'details' => 'required|max:1024',
                    'need' => 'required|numeric|min:1',
                    'address' => 'required',
                ];
                break;
            case 'addImage':
                $rules = [
                    'images' => 'required',
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'before' => 'required|boolean',
                ];
                break;
        }
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
