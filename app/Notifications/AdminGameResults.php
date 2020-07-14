<?php
/**
 * Created by PhpStorm.
 * User: don3d_000
 * Date: 03.11.2019
 * Time: 18:08
 */

namespace App\Notifications;


use App\Notifications\Channels\TelegramChannel;

class AdminGameResults extends GameResults
{
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }
}