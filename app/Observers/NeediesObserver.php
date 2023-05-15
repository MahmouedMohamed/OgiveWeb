<?php

namespace App\Observers;

use App\Models\Ahed\Needy;
use Illuminate\Support\Facades\Cache;

class NeediesObserver
{
    /**
     * Handle the Needy "created" event.
     *
     * @param  \App\Models\Needy  $needy
     * @return void
     */
    public function created(Needy $needy)
    {
    }

    /**
     * Handle the Needy "updated" event.
     *
     * @param  \App\Models\Needy  $needy
     * @return void
     */
    public function updated(Needy $needy)
    {
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
     * Handle the Needy "deleted" event.
     *
     * @param  \App\Models\Needy  $needy
     * @return void
     */
    public function deleted(Needy $needy)
    {
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
     * Handle the Needy "restored" event.
     *
     * @param  \App\Models\Needy  $needy
     * @return void
     */
    public function restored(Needy $needy)
    {
        //
    }

    /**
     * Handle the Needy "force deleted" event.
     *
     * @param  \App\Models\Needy  $needy
     * @return void
     */
    public function forceDeleted(Needy $needy)
    {
        //
    }
}
