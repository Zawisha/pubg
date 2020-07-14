<?php
/**
 * Created by PhpStorm.
 * User: don3d_000
 * Date: 13.09.2019
 * Time: 17:49
 */

namespace App\Notifications\Channels;


use App\Jobs\DeliverTelegram;
use App\Models\TelegramHistory;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;


class TelegramChannel
{
    public function send($notifiable, Notification $notification)
    {
        DeliverTelegram::dispatch($notifiable, $notification)->onQueue('telegram');
    }
}