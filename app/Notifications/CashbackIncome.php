<?php

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CashbackIncome extends Notification implements ShouldQueue
{
    use Queueable;

    public const REASON_PAYMENT = 1;
    public const REASON_GAME = 2;

    var $reason;
    var $userName;
    var $userId;
    var $amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reason, $userName, $userId, $amount)
    {
        $this->reason = $reason;
        $this->userName = $userName;
        $this->userId = $userId;
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
            'broadcast',
            TelegramChannel::class
        ];
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
            ->subject(__('notifications.CashbackIncome.subject'))
            ->greeting(__('notifications.greeting', ['username' => $notifiable->name]))
            ->line(__('notifications.CashbackIncome.text', [
                'amount' => $this->amount,
                'user_name' => $this->userName
            ]))
//            ->line('Новые возможности: <br />' . nl2br($this->rank->description))
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
            'reason' => $this->reason,
            'username' => $this->userName,
            'userId' => $this->userId,
            'text' => __('notifications.CashbackIncome.text', [
                'amount' => $this->amount,
                'user_name' => $this->userName
            ])
        ];
    }
}
