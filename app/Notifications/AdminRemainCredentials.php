<?php

namespace App\Notifications;

use App\Models\Game;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminRemainCredentials extends Notification
{
    use Queueable;


    var $game;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail', TelegramChannel::class];
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
            ->subject(__('notifications.AdminRemainCredentials.subject'))
            ->greeting(__('notifications.greeting', ['username' => $notifiable->name]))
            ->line(__('notifications.AdminRemainCredentials.line'))
            ->action(__('notifications.AdminRemainCredentials.action'), url('/missioncontrol/games/' . $this->game->id . '/edit'));
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
            'text' => __('notifications.AdminRemainCredentials.telegram_text', [
                'game_name' => $this->game->name
            ])
        ];
    }
}
