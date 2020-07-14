<?php

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GameResults extends Notification implements ShouldQueue
{
    use Queueable;

    var $imageName;
    var $isKing;
    var $gameString;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($imageName, $isKing = false, $gameString = '')
    {
        $this->imageName = $imageName;
        $this->isKing = $isKing;
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
        $where = collect([
//            'mail',
            'database',
            'broadcast'
        ]);

//        if (!$notifiable->telegram_ban) {
//            $where->push(TelegramChannel::class);
//        }

        return $where->toArray();
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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $imageUrl = url($this->imageName);
        return [
            'text' =>
                $this->gameString .
                __((!$this->isKing
                    ? 'notifications.GameResults.cabinet_text'
                    : 'notifications.KingGameResults.cabinet_text'), [
                    'image_url' => $imageUrl
                ])
        ];
    }

    public function toTelegram($notifiable)
    {
        return [
            'photo' => $this->imageName,
            'text' =>
                $this->gameString .
                __((!$this->isKing
                    ? 'notifications.GameResults.telegram_text'
                    : 'notifications.KingGameResults.telegram_text'))
        ];
    }
}