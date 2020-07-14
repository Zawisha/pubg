<?php

namespace App\Jobs;

use App\Models\Game;
use App\Models\User;
use App\Notifications\GameResults;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class SendGameResultsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $timeout = 3600;

    var $game;
    var $imageName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Game $game, $imageName)
    {
        $this->game = $game;
        $this->imageName = $imageName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $membersIds = $this->game->members()->wherePivot('visit', true)->pluck('id');
        User::whereNotIn('id', $membersIds)->chunk(1000, function ($users) {
            Notification::send($users, (new GameResults($this->imageName, $this->game->is_king, $this->game->getGameNameNotificationString()))->onQueue('mailing'));
        });
    }
}
