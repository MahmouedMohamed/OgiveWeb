<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\AtaaPrizeCreationActionNotFound;
use App\Exceptions\AtaaPrizeNotFound;
use App\Models\Ataa\AtaaPrize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait AtaaPrizeValidator
{

    /**
     * Returns If Online Transaction exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function prizeExists(String $id)
    {
        $prize = AtaaPrize::find($id);
        if (!$prize)
            throw new AtaaPrizeNotFound();
        return $prize;
    }

    public function preCreationPrizeChecker(Request $request)
    {
        $sameLevelPrize = AtaaPrize::where('active', '=', 1)->where('level', '=', $request['level'])->get()->first();
        if ($sameLevelPrize) {
            //Check if admin want to replace or shift
            $adminChosenAction = $request['action'];
            if ($adminChosenAction == null || ($adminChosenAction != 'replace' && $adminChosenAction != 'shift'))
                throw new AtaaPrizeCreationActionNotFound();
            if ($adminChosenAction == 'replace') {
                //replace => deactivate the old prize, create the new
                $sameLevelPrize->deactivate();
            } else {
                //shift the others where level is bigger
                $biggerLevelPrizes = AtaaPrize::where('active', '=', 1)->where('level', '>=', $sameLevelPrize['level'])->get();
                foreach ($biggerLevelPrizes as $prize) {
                    //update their level
                    $prize->increaseLevel();
                    //update their name if they are auto filled
                    if (str_contains($prize['name'], 'Level'))
                        $prize->updateName();
                }
            }
        }
        return;
    }

    public function validatePrize(Request $request)
    {
        $rules = [
            'userId' => 'required',
            'name' => 'required',
            'arabic_name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'required_markers_collected' => 'required|integer|min:0',
            'required_markers_posted' => 'required|integer|min:0',
            'from' => 'date',
            'to' => 'date|after:from',
            'level' => 'required|integer|min:1',
        ];
        return Validator::make($request->all(), $rules);
    }
}
