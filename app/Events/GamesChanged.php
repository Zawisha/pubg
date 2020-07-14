<?php

namespace App\Events;

use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GamesChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    var $games;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $gamesIds = collect();

        $gamesIds = $gamesIds->merge(Game::whereIn('status', [Game::STATUS_NEW, Game::STATUS_STARTED])
            ->orderBy('planned_at', 'asc')
            ->where('game_code', Game::GAME_PUBG)
            ->notKing()
            ->take(7)
            ->pluck('id'));


        $gamesIds = $gamesIds->merge(Game::whereIn('status', [Game::STATUS_NEW, Game::STATUS_STARTED])
            ->orderBy('planned_at', 'asc')
            ->where('game_code', Game::GAME_CALL_OF_DUTY)
            ->notKing()
            ->take(7)
            ->pluck('id'));

        $gamesIds = $gamesIds->merge(Game::whereIn('status', [Game::STATUS_NEW, Game::STATUS_STARTED])
            ->orderBy('planned_at', 'asc')
            ->where('game_code', Game::GAME_FREEFIRE)
            ->notKing()
            ->take(7)
            ->pluck('id'));

        $this->games = Game::whereIn('id', $gamesIds)
            ->orderBy('planned_at', 'asc')
            ->withCount('members')
            ->get()->map(function ($game) {
                return $game->toArray();
            });
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('App.Game');
    }

    public function broadcastAs()
    {
        return 'games.changed';
    }
}
