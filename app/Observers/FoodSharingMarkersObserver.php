<?php

namespace App\Observers;

use App\Events\FoodSharingMarkerCreated;
use App\Jobs\AtaaAchievementCalculator;
use App\Models\Ataa\FoodSharingMarker;
use Illuminate\Support\Facades\Cache;

class FoodSharingMarkersObserver
{
    /**
     * Handle the FoodSharingMarker "created" event.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return void
     */
    public function created(FoodSharingMarker $foodSharingMarker)
    {
        FoodSharingMarkerCreated::dispatch($foodSharingMarker);
        // dd($foodSharingMarker->user);
        AtaaAchievementCalculator::dispatch($foodSharingMarker->user, $foodSharingMarker, 'Create');

        return Cache::forget('foodsharingmarkers');
    }

    /**
     * Handle the FoodSharingMarker "updated" event.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return void
     */
    public function updated(FoodSharingMarker $foodSharingMarker)
    {
        return Cache::forget('foodsharingmarkers');
    }

    /**
     * Handle the FoodSharingMarker "deleted" event.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return void
     */
    public function deleted(FoodSharingMarker $foodSharingMarker)
    {
        return Cache::forget('foodsharingmarkers');
    }

    /**
     * Handle the FoodSharingMarker "restored" event.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return void
     */
    public function restored(FoodSharingMarker $foodSharingMarker)
    {
        //
    }

    /**
     * Handle the FoodSharingMarker "force deleted" event.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return void
     */
    public function forceDeleted(FoodSharingMarker $foodSharingMarker)
    {
        //
    }
}
