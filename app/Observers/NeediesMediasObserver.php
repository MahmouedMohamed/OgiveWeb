<?php

namespace App\Observers;

use App\Models\Ahed\NeedyMedia;
use Illuminate\Support\Facades\Cache;

class NeediesMediasObserver
{
    /**
     * Handle the NeedyMedia "created" event.
     *
     * @return void
     */
    public function created(NeedyMedia $needyMedia)
    {
        $needy = $needyMedia->needy;
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
     * Handle the NeedyMedia "updated" event.
     *
     * @return void
     */
    public function updated(NeedyMedia $needyMedia)
    {
        $needy = $needyMedia->needy;
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
     * Handle the NeedyMedia "deleted" event.
     *
     * @return void
     */
    public function deleted(NeedyMedia $needyMedia)
    {
        $needy = $needyMedia->needy;
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
     * Handle the NeedyMedia "restored" event.
     *
     * @return void
     */
    public function restored(NeedyMedia $needyMedia)
    {
        //
    }

    /**
     * Handle the NeedyMedia "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(NeedyMedia $needyMedia)
    {
        //
    }
}
