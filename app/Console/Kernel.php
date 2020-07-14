<?php

namespace App\Console;

use App\Jobs\CheckPayments;
use App\Jobs\GameReminderJob;
use App\Models\Notification;
use App\Notifications\GameRemaind;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Notifications\DatabaseNotification;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            GameReminderJob::dispatchNow();
//            \Log::debug('Starting job!');
        })->name("GameReminderJob")->withoutOverlapping()->everyMinute();

        $schedule->call(function () {
            CheckPayments::dispatchNow();
//            \Log::debug('Starting job!');
        })->name("CheckPayments")->withoutOverlapping()->everyTenMinutes();


        $schedule->call(function () {
            DatabaseNotification::where('created_at', '<', now()->subDays(15))->delete();
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
