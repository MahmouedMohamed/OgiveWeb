<?php

namespace App\Observers;

use App\Models\Ahed\Needy;
use App\Models\Ahed\NeedyMedia;
use Illuminate\Support\Facades\Cache;

class NeediesMediasObserver
{
    /**
     * Handle the NeedyMedia "created" event.
     *
     * @param  \App\Models\Ahed\NeedyMedia  $needyMedia
     * @return void
     */
    public function created(NeedyMedia $needyMedia)
    {
        $needy = Needy::find($needyMedia->needy);
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
     * Handle the NeedyMedia "updated" event.
     *
     * @param  \App\Models\Ahed\NeedyMedia  $needyMedia
     * @return void
     */
    public function updated(NeedyMedia $needyMedia)
    {
        $needy = Needy::find($needyMedia->needy);
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
     * Handle the NeedyMedia "deleted" event.
     *
     * @param  \App\Models\Ahed\NeedyMedia  $needyMedia
     * @return void
     */
    public function deleted(NeedyMedia $needyMedia)
    {
        $needy = Needy::find($needyMedia->needy);
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
     * Handle the NeedyMedia "restored" event.
     *
     * @param  \App\Models\Ahed\NeedyMedia  $needyMedia
     * @return void
     */
    public function restored(NeedyMedia $needyMedia)
    {
        //
    }

    /**
     * Handle the NeedyMedia "force deleted" event.
     *
     * @param  \App\Models\Ahed\NeedyMedia  $needyMedia
     * @return void
     */
    public function forceDeleted(NeedyMedia $needyMedia)
    {
        //
    }
}
