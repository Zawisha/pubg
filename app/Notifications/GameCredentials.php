<?php

namespace App\Notifications;

use App\Models\Game;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GameCredentials extends Notification implements ShouldQueue
{
    use Queueable;

    var $team;
    var $game;
    var $gameString;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($game, $team)
    {
        $this->team = $team;
        $this->game = $game;
        try {
            $this->gameString = Game::GAME_NAMES[$game['game_code']] . "\n" .
                "------\n";
        } catch (\Throwable $ex) {
            \Log::error($ex);
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
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
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('notifications.GameCredentials.subject'))
            ->greeting(__('notifications.greeting', ['username' => $notifiable->name]))
            ->line($this->gameString)
            ->line(__('notifications.GameCredentials.line1'))
            ->line(__('notifications.GameCredentials.line2'))
            ->line(__('notifications.GameCredentials.login', ['login' => $this->game['login']]))
            ->line(__('notifications.GameCredentials.password', ['password' => $this->game['password']]))
            ->line(($this->game['type'] !== Game::TYPE_SOLO
                ? __('notifications.GameCredentials.team', ['team' => $this->team])
                : ''))
            ->line('------')
            ->line(__('notifications.GameCredentials.rules_title'))
            ->line(__('notifications.GameCredentials.rules1'))
            ->line(__('notifications.GameCredentials.rules2'))
            ->line(__('notifications.GameCredentials.rules3'))
            ->line(__('notifications.GameCredentials.rules4'))
            ->line(__('notifications.GameCredentials.rules5'))
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
            'team' => $this->team,
            'login' => $this->game['login'],
            'password' => $this->game['password'],
            'text' =>
                $this->gameString .
                __('notifications.GameCredentials.line1') . "\n"
                . __('notifications.GameCredentials.line2') . "\n"
                . '------' . "\n"
                . __('notifications.GameCredentials.login', ['login' => $this->game['login']]) . "\n"
                . __('notifications.GameCredentials.password', ['password' => $this->game['password']]) . "\n"
                . ($this->game['type'] !== Game::TYPE_SOLO
                    ? "\n" . __('notifications.GameCredentials.team', ['team' => $this->team])
                    : '')
                . "\n" . '------' . "\n"
                . __('notifications.GameCredentials.rules_title') . "\n"
                . __('notifications.GameCredentials.rules1') . "\n"
                . __('notifications.GameCredentials.rules2') . "\n"
                . __('notifications.GameCredentials.rules3') . "\n"
                . __('notifications.GameCredentials.rules4') . "\n"
                . __('notifications.GameCredentials.rules5') . "\n"

        ];
    }
}
