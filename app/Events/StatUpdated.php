<?php

namespace App\Events;

use App\Models\Game;
use App\Models\GameRu;
use App\Models\User;
use App\Models\UserRu;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\DB;

class StatUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stat;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->stat = [];

        $this->stat['users'] = User::count() + UserRu::count();
        $this->stat['games'] = Game::where('status', Game::STATUS_FINISHED)->sum('mul')
            + GameRu::where('status', Game::STATUS_FINISHED)->sum('mul');
        $this->stat['total_payed'] = Game::select(DB::raw('sum(total_payed2) as tp2, sum(total_payed) as tp'))
            ->first();

        $tpRu = GameRu::select(DB::raw('sum(total_payed2) as tp2, sum(total_payed) as tp'))
            ->first();

        $this->stat['total_payed'] = $this->stat['total_payed']->tp + $this->stat['total_payed']->tp2
            + round(($tpRu->tp + $tpRu->p2) / 70);;
    }

    public function broadcastOn()
    {
        return new Channel('App.Game');
    }

    public function broadcastAs()
    {
        return 'stat.updated';
    }
}
