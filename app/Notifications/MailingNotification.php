<?php

namespace App\Notifications;

use App\Models\Mailing;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Mailing
     */
    var $mailing;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mailing $mailing)
    {
        $this->mailing = $mailing;
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

        if ($this->mailing->to_lk && $this->mailing->short_message) {
            $where->push('database');
            $where->push('broadcast');
        }

        if ($this->mailing->to_email && !$notifiable->no_mail && $this->mailing->message) {
            $where->push('mail');
        }

        if ($this->mailing->to_bot && $this->mailing->message) {
            $where->push(TelegramChannel::class);
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
        $mail = (new MailMessage)
            ->subject(__('notifications.MailingNotification.subject'))
            ->greeting(__('notifications.greeting', ['username' => $notifiable->name]));

        foreach (explode("\n", $this->mailing->message) as $line) {
            $mail->line($line);
        }

        //->line($this->mailing->message)

        $mail->action(__('notifications.action'), url('/'));

        return $mail;
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
            'mailing_id' => $this->mailing->id,
            'text' => $this->mailing->short_message
        ];
    }

    public function toTelegram($notifiable)
    {
        return [
            'mailing_id' => $this->mailing->id,
            'photo' => $this->mailing->image,
            'text' => $this->mailing->message
        ];
    }
}
