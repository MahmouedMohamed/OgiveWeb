<?php

namespace App\Traits\ControllersTraits;

use App\Jobs\AtaaAchievementCalculator;
use App\Models\AtaaPrize;
use App\Models\FoodSharingMarker;
use App\Models\User;

/**
 *
 */
trait AtaaActionHandler
{
    public function handleMarkerCreated(User $user, FoodSharingMarker $foodSharingMarker)
    {
        AtaaAchievementCalculator::dispatch($user, $foodSharingMarker, 'Create');
    }
    public function handleMarkerCollected(User $user, FoodSharingMarker $foodSharingMarker)
    {
        AtaaAchievementCalculator::dispatch($user, $foodSharingMarker, 'Collect');
    }
    public function handleMarkerExistingAction(FoodSharingMarker $foodSharingMarker, bool $foodSharingMarkerExists)
    {
        if ($foodSharingMarkerExists)
            return;
        //TODO: if marker doesn't exists for 100 times for the same user
        //->
        //Ban this marker publisher for publishing again
        //TODO: Count
    }
    public function handleMarkerDeleted($user)
    {
        $user->ataaAchievement->decreaseMarkersPosted();
        AtaaPrize::prizesReview($user);
    }
}
