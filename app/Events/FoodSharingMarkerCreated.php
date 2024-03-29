<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FoodSharingMarkerCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The foodSharingMarker instance.
     *
     * @var \App\Models\Ataa\FoodSharingMarker
     */
    public $foodSharingMarker;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($foodSharingMarker)
    {
        $this->foodSharingMarker = $foodSharingMarker;
    }

    public function broadcastAs()
    {
        return 'FoodSharingMarkerCreated';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->foodSharingMarker->nationality);
    }
}
