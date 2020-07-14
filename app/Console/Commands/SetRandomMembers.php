<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\User;
use Illuminate\Console\Command;

class SetRandomMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:random_members {gameId} {members}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $game = Game::find($this->argument('gameId'));

        $members = User::inRandomOrder()->take($this->argument('members') / 2)->get();

        $game->members()->sync($members->pluck('id'));

        $members = User::inRandomOrder()->take($this->argument('members') / 2 - 5)
            ->whereNotIn('id', $members->pluck('id'))
            ->where('kd', '>', 0)
            ->get();

        $game->members()->attach($members->pluck('id'));

        $members = User::inRandomOrder()->take(5)
            ->whereNotIn('id', $game->members()->pluck('id'))
            ->where('kd', '>=', 3)
            ->get();

        $game->members()->attach($members->pluck('id'));
    }
}
