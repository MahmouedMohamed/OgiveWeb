<?php

namespace App\Events;

use App\Services\SegmentService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $user, private array $extraParams)
    {
    }

    public function handle()
    {
        $segmentService = new SegmentService();
        $segmentService->identify($this->user, $this->extraParams);
        $segmentService->track(class_basename($this), $this->user, $this->extraParams);
        $segmentService->flush();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
