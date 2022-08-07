<?php

namespace App\Traits\ControllersTraits;

use App\Models\Ataa\FoodSharingMarker;
use App\Exceptions\FoodSharingMarkerNotFound;
use App\Exceptions\FoodSharingMarkerIsCollected;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
    public function validateMarker(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'createdBy' => 'required',
                    'latitude' => 'required|numeric|min:0',
                    'longitude' => 'required|numeric|min:0',
                    'type' => 'required|in:Food,Drink,Both of them',
                    'description' => 'required|max:1024',
                    'quantity' => 'required|integer|min:1|max:10',
                    'priority' => 'required|integer|min:1|max:10'
                ];
                break;
            case 'update':
                $rules = [
                    'latitude' => 'required|numeric|min:0',
                    'longitude' => 'required|numeric|min:0',
                    'type' => 'required|in:Food,Drink,Both of them',
                    'description' => 'required|max:1024',
                    'quantity' => 'required|integer|min:1|max:10',
                    'priority' => 'required|integer|min:1|max:10'
                ];
                break;
        }
        return Validator::make($request->all(), $rules);
    }
}
