<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\FoodSharingMarkerIsCollected;
use App\Exceptions\FoodSharingMarkerNotFound;
use App\Models\Ataa\FoodSharingMarker;

trait FoodSharingMarkerValidator
{
    /**
     * Returns If FoodSharingMarker exists or not.
     *
     * @return mixed
     */
    public function foodSharingMarkerExists(string $id)
    {
        $foodSharingMarker = FoodSharingMarker::find($id);
        if (! $foodSharingMarker) {
            throw new FoodSharingMarkerNotFound();
        }

        return $foodSharingMarker;
    }

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
