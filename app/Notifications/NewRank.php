<?php

namespace App\Notifications;

use App\Models\Rank;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewRank extends Notification implements ShouldQueue
//    implements ShouldBroadcast
{
    use Queueable;

    var $rank;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Rank $newRank)
    {
        $this->rank = $newRank;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $targets = collect([
            'database',
            'broadcast',
            TelegramChannel::class
        ]);

        if (!$notifiable->no_mail) {
            $targets->push('mail');
        }

        return $targets->toArray();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $msg = (new MailMessage)
            ->subject(__('notifications.NewRank.subject'))
            ->greeting(__('notifications.greeting', ['username' => $notifiable->name]))
            ->line(__('notifications.NewRank.rank_text', [
                'rank_name' => $this->rank->name
            ]))
            ->line(__('notifications.NewRank.pos_title'));

        foreach (explode("\n", $this->rank->description) as $line) {
            $msg->line($line);
        }

        $msg->action(__('notifications.action'), url('/'));

        return $msg;
    }

//    public function broadcastOn()
//    {
//        return new PrivateChannel('App.User.' . $this->noti);
//    }
//
//    public function broadcastAs()
//    {
//        return 'notification';
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'text' => __('notifications.NewRank.rank_text', [
                'rank_name' => $this->rank->name
            ])
        ];
    }
}
