<?php

namespace App\Providers;

use App\Models\Ahed\Needy;
use App\Models\Ahed\NeedyMedia;
use App\Models\Ahed\OfflineTransaction;
use App\Models\Ahed\OnlineTransaction;
use App\Observers\NeediesObserver;
use App\Observers\NeediesMediasObserver;

use App\Models\Ataa\FoodSharingMarker;
use App\Observers\FoodSharingMarkersObserver;

use App\Models\BreedMe\Pet;
use App\Observers\OfflineTransactionObserver;
use App\Observers\OnlineTransactionObserver;
use App\Observers\PetsObserver;

use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Needy::observe(NeediesObserver::class);
        NeedyMedia::observe(NeediesMediasObserver::class);
        FoodSharingMarker::observe(FoodSharingMarkersObserver::class);
        Pet::observe(PetsObserver::class);
        OnlineTransaction::observe(OnlineTransactionObserver::class);
        OfflineTransaction::observe(OfflineTransactionObserver::class);
    }
}
