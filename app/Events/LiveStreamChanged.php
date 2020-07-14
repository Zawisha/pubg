<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LiveStreamChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    var $url;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function broadcastOn()
    {
        return new Channel('App.Game');
    }

    public function broadcastAs()
    {
        return 'stream.changed';
    }
}
