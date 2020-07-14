<?php

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GameCanceled extends Notification implements ShouldQueue
{
    use Queueable;

    var $date;
    var $gameString;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($date, $gameString = '')
    {
        $this->date = $date;
        $this->gameString = $gameString;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $targets = collect(['database', 'broadcast', TelegramChannel::class]);

        if (!$notifiable->no_mail) {
            $targets->push('mail');
        }

        return $targets->toArray();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('notifications.GameCanceled.subject'))
            ->greeting(__('notifications.greeting', ['username' => $notifiable->name]))
            ->line($this->gameString)
            ->line(__('notifications.GameCanceled.text', [
                'date' => $this->date
            ]))
            ->action(__('notifications.action'), url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'text' => $this->gameString . __('notifications.GameCanceled.text', [
                    'date' => $this->date
                ])
        ];
    }
}
