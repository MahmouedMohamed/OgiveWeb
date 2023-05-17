<?php

namespace App\Observers;

use App\Models\Ahed\Needy;
use App\Models\Ahed\OfflineTransaction;
use Illuminate\Support\Facades\Cache;

class OfflineTransactionObserver
{
    /**
     * Handle the OfflineTransaction "created" event.
     *
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return void
     */
    public function created(OfflineTransaction $offlineTransaction)
    {
        //
    }

    /**
     * Handle the OfflineTransaction "updated" event.
     *
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return void
     */
    public function updated(OfflineTransaction $offlineTransaction)
    {
        $needy = Needy::find($offlineTransaction->needy_id)->first();
        $index = 1;
        if ($needy->severity >= 7) {
            while (true) {
                if (Cache::has('urgentNeedies-'.$index)) {
                    Cache::forget('urgentNeedies-'.$index);
                    $index++;
                } else {
                    break;
                }
            }

            return;
        }
        while (true) {
            if (Cache::has('needies-'.$index)) {
                Cache::forget('needies-'.$index);
                $index++;
            } else {
                break;
            }
        }

    }

    /**
     * Handle the OfflineTransaction "deleted" event.
     *
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return void
     */
    public function deleted(OfflineTransaction $offlineTransaction)
    {
        //
    }

    /**
     * Handle the OfflineTransaction "restored" event.
     *
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return void
     */
    public function restored(OfflineTransaction $offlineTransaction)
    {
        //
    }

    /**
     * Handle the OfflineTransaction "force deleted" event.
     *
     * @param  \App\Models\OfflineTransaction  $offlineTransaction
     * @return void
     */
    public function forceDeleted(OfflineTransaction $offlineTransaction)
    {
        //
    }
}
