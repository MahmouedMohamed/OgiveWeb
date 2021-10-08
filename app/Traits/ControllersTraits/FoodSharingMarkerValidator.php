<?php

namespace App\Traits\ControllersTraits;

use App\Models\FoodSharingMarker;
use App\Exceptions\FoodSharingMarkerNotFound;
use App\Exceptions\FoodSharingMarkerIsCollected;

trait FoodSharingMarkerValidator
{

    /**
     * Returns If FoodSharingMarker exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function foodSharingMarkerExists(String $id)
    {
        $foodSharingMarker = FoodSharingMarker::find($id);
        if (!$foodSharingMarker)
            throw new FoodSharingMarkerNotFound();
        return $foodSharingMarker;
    }
    /**
     * Returns If FoodSharingMarker is collected or not.
     *
     * @param FoodSharingMarker $foodSharingMarker
     * @return mixed
     */
    public function foodSharingMarkerIsCollected(FoodSharingMarker $foodSharingMarker)
    {
        if ($foodSharingMarker->collected == 1)
            throw new FoodSharingMarkerIsCollected();
        return;
    }
}
