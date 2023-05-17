<?php

namespace App\Observers;

use App\Models\BreedMe\Pet;

class PetsObserver
{
    /**
     * Handle the Pet "created" event.
     *
     * @return void
     */
    public function created(Pet $pet)
    {
        //
    }

    /**
     * Handle the Pet "updated" event.
     *
     * @return void
     */
    public function updated(Pet $pet)
    {
        //
    }

    /**
     * Handle the Pet "deleted" event.
     *
     * @return void
     */
    public function deleted(Pet $pet)
    {
        //
    }

    /**
     * Handle the Pet "restored" event.
     *
     * @return void
     */
    public function restored(Pet $pet)
    {
        //
    }

    /**
     * Handle the Pet "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Pet $pet)
    {
        //
    }
}
