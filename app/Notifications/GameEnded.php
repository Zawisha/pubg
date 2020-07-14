<?php

namespace App\Notifications;

use App\Models\Game;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GameEnded extends Notification implements ShouldQueue
{
    use Queueable;

    var $amount;
    var $kills;
    var $tops;
    var $date;
    var $winTable;
    var $bonusUsed;
    var $isKing;
    var $gameCode;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($amount, $kills, $tops, $date, $winTable, $bonusUsed, $isKing = false, $gameName = Game::GAME_PUBG)
    {
        $this->amount = $amount;
        $this->kills = $kills;
        $this->tops = $tops;
        $this->date = $date;
        $this->winTable = $winTable;
        $this->bonusUsed = $bonusUsed;
        $this->isKing = $isKing;
        $this->gameCode = $gameName;
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

        if (!$notifiable->telegram_ban) {
            $where->push(TelegramChannel::class);
        }

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
            ->subject(__(($this->isKing
                ? 'notifications.GameEnded.king_subject'
                : 'notifications.GameEnded.subject')))
            ->greeting(__('notifications.greeting', ['username' => $notifiable->name]))
            ->line(__(($this->isKing
                    ? 'notifications.GameEnded.king_complete_text'
                    : 'notifications.GameEnded.complete_text'), [
                    'amount' => $this->amount,
                    'kills' => $this->kills
                ])
                . ((count($this->tops) == 1)
                    ? __('notifications.GameEnded.best_player') . ' ' . $this->tops[0]
                    : __('notifications.GameEnded.best_players') . ' ' . implode(', ', $this->tops)))
            ->line($this->getKillsAddText($notifiable, false))
//            ->line('Новые возможности: <br />' . nl2br($this->rank->description))
            ->action(__('notifications.action'), url('/'));
//            ->line('The introduction to the notification.')
//            ->action('Notification Action', url('/'))
//            ->line('Thank you for using our application!');
    }

    public function preapareImage($notifiable)
    {

        $bgPath = 'images/top_players_bg.png';

        if ($this->gameCode == Game::GAME_PUBG) {
            $bgPath = 'images/top_players_bg_pubg.png';
        }

        if ($this->gameCode == Game::GAME_CALL_OF_DUTY) {
            $bgPath = 'images/top_players_bg_cod.png';
        }

        if ($this->gameCode == Game::GAME_FREEFIRE) {
            $bgPath = 'images/top_players_bg_freefire.png';
        }

        // 960x1760
        $image = imagecreatefrompng(public_path($bgPath));

        $imageM = imagecreatefrompng(public_path('images/top_players_icons.png'));
        // 33x34
        $rankImgs = [];
        $rankImgs[0] = imagecreatefrompng(public_path('images/top-rank-1.png'));
        $rankImgs[1] = imagecreatefrompng(public_path('images/top-rank-2.png'));
        $rankImgs[2] = imagecreatefrompng(public_path('images/top-rank-3.png'));
        $fontFile = public_path('fonts/hf.ttf');
        $nikFont = public_path('fonts/ar.ttf');

        $colorOrange = imagecolorallocate($image, 255, 192, 0);
        $colorWhite = imagecolorallocate($image, 255, 255, 255);

        $headerText = __('notifications.GameEnded.image.title');
        $bigFontSize = 65;
        // 0 1 4 5
        $textBox = imagettfbbox($bigFontSize, 0, $fontFile, $headerText);
        $w = $textBox[4] - $textBox[0];

        imagettftext($image, $bigFontSize, 0,
            960 / 2 - $w / 2, 80 + 65,
            $colorOrange,
            $fontFile,
            $headerText);

        $dateText = $this->date->format(__('notifications.GameEnded.image.date_format'));

        $dateFontSize = 34;

        $textBox = imagettfbbox($dateFontSize, 0, $fontFile, $dateText);
        $w = $textBox[4] - $textBox[0];

        imagettftext($image, $dateFontSize, 0,
            960 / 2 - $w / 2, 176 + 34,
            $colorWhite,
            $fontFile,
            $dateText);

        $textLine1 = __('notifications.GameEnded.image.subtitle1');
        $textLine2 = __(($this->isKing
            ? 'notifications.GameEnded.image.king_subtitle2'
            : 'notifications.GameEnded.image.subtitle2'));

        $textBox = imagettfbbox($dateFontSize, 0, $fontFile, $textLine1);
        $w = $textBox[4] - $textBox[0];

        imagettftext($image, $dateFontSize, 0,
            960 / 2 - $w / 2, 1628 + 34,
            $colorWhite,
            $fontFile,
            $textLine1);

        $textBox = imagettfbbox($dateFontSize, 0, $fontFile, $textLine2);
        $w = $textBox[4] - $textBox[0];

        imagettftext($image, $dateFontSize, 0,
            960 / 2 - $w / 2, 1680 + 34,
            $colorWhite,
            $fontFile,
            $textLine2);


        $colsWidth = 860;
        $cols = ceil(count($this->winTable) / 34);
        $keys = array_keys($this->winTable);

        $topKills = [];
        foreach ($this->winTable as $elem) {
            $topKills[$elem['kills']] = 1;
        }

        $topKills = array_keys($topKills);
        arsort($topKills);
        $topKills = array_values($topKills);
        $topKills = array_slice($topKills, 0, 3);
        $topKills = array_flip($topKills);

//        print_r($topKills);

//        imagecopy($image, $imageM, 35, 317, 0, 0, 33, 109);

        $nikFontSize = 17;
        for ($col = 0; $col < $cols; $col++) {
            $colX = 50 + $colsWidth / $cols * $col;
            for ($row = 0; $row < 34 && isset($keys[$col * 34 + $row]); $row++) {
                $totalIdx = $col * 34 + $row;

                if (isset($topKills[$this->winTable[$keys[$totalIdx]]['kills']])) {
                    $rankImg = $rankImgs[2];
                    try {
                        $rankImg = $rankImgs[$topKills[$this->winTable[$keys[$totalIdx]]['kills']]];
                    } catch (\Throwable $er) {
                    }

                    imagecopy($image, $rankImg, $colX - 10, 340 + 38 * $row - 24, 0, 0, 33, 34);

                    imagettftext($image, $nikFontSize, 0,
                        $colX + 5, 340 + 38 * $row,
                        $colorOrange,
                        $nikFont,
                        '    '
                        . $this->winTable[$keys[$totalIdx]]['name'] . ' '
                        . $this->winTable[$keys[$totalIdx]]['kills']);
                } else {
                    imagettftext($image, $nikFontSize, 0,
                        $colX, 335 + 38 * $row,
                        ($this->winTable[$keys[$totalIdx]]['name'] == $notifiable->name) ? $colorOrange : $colorWhite,
                        $nikFont,
                        transliterator_transliterate('Any-Latin; Latin-ASCII;',
                            ($totalIdx + 1) . '. '
                            . $this->winTable[$keys[$totalIdx]]['name'] . ' '
                            . $this->winTable[$keys[$totalIdx]]['kills']));
                }
            }
        }

        imagepng($image, public_path('images/notifications/' . $notifiable->id . '.png'));
        return 'images/notifications/' . $notifiable->id . '.png';
    }


    public function getKillsAddText($notifiable, $toBot = true)
    {
        // Варианты:
        // 0 киллов и бонус не использован
        if (($this->kills == 0) && !$this->bonusUsed) {
            return ($toBot ? "\n------\n" : "\n")
                . __('notifications.GameEnded.no_frags');
        }
        // 0 киллов и бонус идет
        if ($this->bonusUsed && $notifiable->bonus_games > 0) {
            return ($toBot ? "\n------\n" : "\n")
                . (($notifiable->bonus_games > 1)
                    ? __('notifications.GameEnded.bonuses_remain')
                    : __('notifications.GameEnded.bonus_remain', [
                        'games' => $notifiable->bonus_games
                    ]));
        }

        // 0 киллов и бонус закончился
        return '';
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
            'amount' => $this->amount,
            'kills' => $this->kills,
            'tops' => $this->tops,
            'text' =>
                Game::GAME_NAMES[$this->gameCode] . "\n" .
                "-----\n" .
                __(($this->isKing
                    ? 'notifications.GameEnded.king_complete_text'
                    : 'notifications.GameEnded.complete_text'), [
                    'amount' => $this->amount,
                    'kills' => $this->kills
                ])
                . ((count($this->tops) == 1)
                    ? __('notifications.GameEnded.best_player') . ' ' . $this->tops[0]
                    : __('notifications.GameEnded.best_players') . ' ' . implode(', ', $this->tops))
                . $this->getKillsAddText($notifiable, false)
        ];
    }

    public function toTelegram($notifiable)
    {
        return [
            'photo' => $this->preapareImage($notifiable),
            'text' =>
                Game::GAME_NAMES[$this->gameCode] . "\n" .
                "-----\n" .
                __(($this->isKing
                    ? 'notifications.GameEnded.king_complete_text'
                    : 'notifications.GameEnded.complete_text'), [
                    'amount' => $this->amount,
                    'kills' => $this->kills
                ])
                . ((count($this->tops) == 1)
                    ? __('notifications.GameEnded.best_player') . ' ' . $this->tops[0]
                    : __('notifications.GameEnded.best_players') . ' ' . implode(', ', $this->tops))
                . $this->getKillsAddText($notifiable)
        ];
    }
}
