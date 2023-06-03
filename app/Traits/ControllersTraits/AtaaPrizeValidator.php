<?php

namespace App\Traits\ControllersTraits;

use App\Models\Ataa\AtaaPrize;

trait AtaaPrizeValidator
{
    public function preCreationPrizeChecker(string $level, string $action)
    {
        $sameLevelPrize = AtaaPrize::where('active', '=', 1)->where('level', '=', $level)->get()->first();
        if ($sameLevelPrize) {
            if ($action == 'replace') {
                //replace => deactivate the old prize, create the new
                $sameLevelPrize->deactivate();
            } else {
                //shift the others where level is bigger
                $biggerLevelPrizes = AtaaPrize::where('active', '=', 1)->where('level', '>=', $sameLevelPrize['level'])->get();
                foreach ($biggerLevelPrizes as $prize) {
                    //update their level
                    $prize->increaseLevel();
                    //update their name if they are auto filled
                    if (str_contains($prize['name'], 'Level')) {
                        $prize->updateName();
                    }
                }
            }
        }

    }
}
