<?php

namespace App\Notifications;

use App\Models\Game;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GameRemaind extends Notification implements ShouldQueue
{
    use Queueable;

    var $game;
    var $hour;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Game $game, $hour = true)
    {
        $this->game = $game;
        $this->hour = $hour;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $where = collect();

        $where->push('database');
        $where->push('broadcast');

        if (!$notifiable->telegram_ban) {
            $where->push(TelegramChannel::class);
        }

        if (!$this->hour && !$notifiable->no_mail) {
            $where->push('mail');
        }

        return $where->toArray();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('notifications.GameRemaind.subject'))
            ->greeting(__('notifications.greeting', ['username' => $notifiable->name]))
            ->line($this->game->getGameName())
            ->line(__('notifications.GameRemaind.text_30minutes'))
            ->action(__('notifications.action'), url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'text' =>
                $this->game->getGameNameNotificationString().
                $this->hour
                ? __('notifications.GameRemaind.text_60minutes')
                : __('notifications.GameRemaind.text_30minutes')
        ];
    }
}
