<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\AtaaAchievementNotFound;

trait AtaaAchievementValidator
{

    /**
     * Returns If User exists or not.
     *
     * @param String|nullable $id
     * @return mixed
     */
    public function ataaAchievementExists($id)
    {
        if (!$id)
            throw new AtaaAchievementNotFound();
        return;
    }
}
