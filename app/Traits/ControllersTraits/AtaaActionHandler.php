<?php

namespace App\Traits\ControllersTraits;

use App\Jobs\AtaaAchievementCalculator;
use App\Models\Ataa\AtaaPrize;
use App\Models\Ataa\FoodSharingMarker;
use App\Models\BaseUserModel;
use App\Models\User;

/**
 *
 */
trait AtaaActionHandler
{
    public function handleMarkerCollected(BaseUserModel $user, FoodSharingMarker $foodSharingMarker)
    {
        AtaaAchievementCalculator::dispatch($user, $foodSharingMarker, 'Collect');
    }
    public function handleMarkerExistingAction(FoodSharingMarker $foodSharingMarker, bool $foodSharingMarkerExists)
    {
        if ($foodSharingMarkerExists)
            return;
        //TODO: if marker doesn't exists for 10 times for the same user
        //->
        //Ban this marker publisher for publishing again
        //TODO: Count
    }
    public function handleMarkerDeleted(BaseUserModel $user)
    {
        $user->ataaAchievement->decreaseMarkersPosted();
        AtaaPrize::prizesReview($user);
    }
}
