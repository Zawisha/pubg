<?php

namespace App\Jobs;

use App\Models\Game;
use App\Models\Mailing;
use App\Models\User;
use App\Notifications\MailingNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class DeliverMailings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $timeout = 3600;

    var $mailing;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mailing $mailing)
    {
        $this->mailing = $mailing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $query = null;

        if ($this->mailing->ranks && count($this->mailing->ranks) > 0) {
            $query = User::whereIn('rank_id', $this->mailing->ranks);
        } else {
            $query = User::where('id', '>', 0);
        }

        if ($this->mailing->users->count()) {
            $query->whereIn('id', $this->mailing->users->pluck('id'));
        }

        if ($this->mailing->games->count() > 0) {
            $query->whereHas('relatedGames', function ($q) {
                $q->whereIn('id', $this->mailing->games->pluck('id'));
            });
        }

        if ($this->mailing->min_balance > 0) {
            $query->where('balance', '>=', $this->mailing->min_balance);
        }

        if ($this->mailing->max_balance > 0) {
            $query->where('balance', '<=', $this->mailing->max_balance);
        }

        if ($this->mailing->created_from) {
            $query->where('created_at', '>=', $this->mailing->created_from->startOfDay());
        }

        if ($this->mailing->created_to) {
            $query->where('created_at', '<=', $this->mailing->created_to->endOfDay());
        }

        if ($this->mailing->membership_type == Mailing::MEMBERSHIP_MEMBER) {
            $query->has('relatedGames');
        }

        if ($this->mailing->membership_type == Mailing::MEMBERSHIP_NOT_MEMBER) {
            $query->doesntHave('relatedGames');
        }

        if ($this->mailing->game_code != null) {
            $columnName = 'pubg_id';

            if ($this->mailing->game_code == Game::GAME_FREEFIRE) {
                $columnName = 'freefire_id';
            }

            if ($this->mailing->game_code == Game::GAME_CALL_OF_DUTY) {
                $columnName = 'cod_id';
            }

            // $query->whereNotNull($columnName);
            $query->where(function($query) use ($columnName) {
                $query->whereNotNull($columnName)
                      ->orWhereJsonContains('selected_games', (string) $this->mailing->game_code);
            });
        }

//        debug($query->get());

        $query->chunk(1000, function ($users) {
            Notification::send($users, (new MailingNotification($this->mailing))->onQueue('mailing'));
        });
    }
}
