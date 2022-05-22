<?php

namespace App\Observers;

use App\Models\Ahed\Needy;
use App\Models\Ahed\OnlineTransaction;
use Illuminate\Support\Facades\Cache;

class OnlineTransactionObserver
{
    /**
     * Handle the OnlineTransaction "created" event.
     *
     * @param  \App\Models\OnlineTransaction  $onlineTransaction
     * @return void
     */
    public function created(OnlineTransaction $onlineTransaction)
    {
        $needy = Needy::find($onlineTransaction->needy_id)->first();
        $index = 1;
        if ($needy->severity >= 7) {
            while (true) {
                if (Cache::has('urgentNeedies-' . $index)) {
                    Cache::forget('urgentNeedies-' . $index);
                    $index++;
                } else
                    break;
            }
            return;
        }
        while (true) {
            if (Cache::has('needies-' . $index)) {
                Cache::forget('needies-' . $index);
                $index++;
            } else
                break;
        }
        return;
    }

    /**
     * Handle the OnlineTransaction "updated" event.
     *
     * @param  \App\Models\OnlineTransaction  $onlineTransaction
     * @return void
     */
    public function updated(OnlineTransaction $onlineTransaction)
    {
        //
    }

    /**
     * Handle the OnlineTransaction "deleted" event.
     *
     * @param  \App\Models\OnlineTransaction  $onlineTransaction
     * @return void
     */
    public function deleted(OnlineTransaction $onlineTransaction)
    {
        //
    }

    /**
     * Handle the OnlineTransaction "restored" event.
     *
     * @param  \App\Models\OnlineTransaction  $onlineTransaction
     * @return void
     */
    public function restored(OnlineTransaction $onlineTransaction)
    {
        //
    }

    /**
     * Handle the OnlineTransaction "force deleted" event.
     *
     * @param  \App\Models\OnlineTransaction  $onlineTransaction
     * @return void
     */
    public function forceDeleted(OnlineTransaction $onlineTransaction)
    {
        //
    }
}
