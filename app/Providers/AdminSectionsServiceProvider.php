<?php

namespace App\Providers;

use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        \App\Models\User::class => \App\Http\Sections\Users::class,
        \App\Models\Rank::class => \App\Http\Sections\Ranks::class,
        \App\Models\Game::class => \App\Http\Sections\Games::class,
        \App\Models\Games\GamePUBG::class => \App\Http\Sections\Games\PUBGGames::class,
        \App\Models\Games\GameFreeFire::class => \App\Http\Sections\Games\FreeFireGames::class,
        \App\Models\Games\GameCallOfDuty::class => \App\Http\Sections\Games\CallOfDutyGames::class,
        \App\Models\DoubleGames\DoubleGamePUBG::class => \App\Http\Sections\DoubleGames\DoublePUBGGames::class,
        \App\Models\DoubleGames\DoubleGameFreeFire::class => \App\Http\Sections\DoubleGames\DoubleFreeFireGames::class,
        \App\Models\DoubleGames\DoubleGameCallOfDuty::class => \App\Http\Sections\DoubleGames\DoubleCallOfDutyGames::class,
        \App\Models\DoubleGame::class => \App\Http\Sections\DoubleGames::class,
        \App\Models\KingGame::class => \App\Http\Sections\KingGames::class,
        \App\Models\Content::class => \App\Http\Sections\Contents::class,
        \App\Models\Admin::class => \App\Http\Sections\Admins::class,
        \App\Models\Mailing::class => \App\Http\Sections\Mailings::class,
        \App\Models\Blogger::class => \App\Http\Sections\Bloggers::class,
        \App\Models\LiveBroadcast::class => \App\Http\Sections\LiveBroadcasts::class,
        \App\Models\TelegramHistory::class => \App\Http\Sections\TelegramMessages::class,
        \App\Models\AdminAuthHistory::class => \App\Http\Sections\AdminLoginsHistory::class,
        \App\Models\WithdrawTransaction::class => \App\Http\Sections\Payouts::class,
    ];

    /**
     * Register sections.
     *
     * @param \SleepingOwl\Admin\Admin $admin
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
        //

        parent::boot($admin);
    }
}
