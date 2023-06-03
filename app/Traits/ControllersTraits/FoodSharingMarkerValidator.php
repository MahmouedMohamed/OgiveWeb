<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\FoodSharingMarkerIsCollected;
use App\Models\Ataa\FoodSharingMarker;

trait FoodSharingMarkerValidator
{
    /**
     * Returns If FoodSharingMarker is collected or not.
     *
     * @return mixed
     */
    public function foodSharingMarkerIsCollected(FoodSharingMarker $foodSharingMarker)
    {
        if ($foodSharingMarker->collected == 1) {
            throw new FoodSharingMarkerIsCollected();
        }

    }
}
