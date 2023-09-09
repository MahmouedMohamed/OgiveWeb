<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendSegmentNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        switch ($event::class) {
            case UserRegistered::class:
                $event->handle();
                break;
            default:
                Log::warning('Event Not Found');
        }
    }
}
