<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GameTeamsChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    var $gameId;
    var $teams;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gameId, $teams)
    {
        $this->gameId = $gameId;
        $this->teams = $teams;
    }

    public function broadcastOn()
    {
        return new Channel('App.Game');
    }

    public function broadcastAs()
    {
        return 'game.teams_changed';
    }
}
