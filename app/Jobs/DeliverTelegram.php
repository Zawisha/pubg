<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Models\TelegramHistory;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class DeliverTelegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $notifiable;
    var $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notifiable, Notification $notification)
    {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
    }


    protected function getBiggestFile($result)
    {
        try {
//            Log::debug($result->photo);
            $biggest = collect($result->photo)->sortBy('file_size')->last();
            return $biggest['file_id'];
//            Log::debug($biggest);
        } catch (\Throwable $er) {
            Log::debug($er);
        }
        return false;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notifiable = $this->notifiable;
        $notification = $this->notification;

        if (!$notifiable->telegram_id) {
            return;
        }

        if ($notifiable->telegram_ban) {
            return;
        }

        $data = [];

        if (method_exists($notification, 'toTelegram')) {
            $data = $notification->toTelegram($notifiable);
        } else {
            $data = $notification->toArray($notifiable);
        }

        /** @var \Telegram\Bot\Traits\Telegram $bot */
        $bot = Telegram::bot();
        $result = null;

        try {
            if (!empty($data['photo'])) {
                $image = public_path($data['photo']);
                $file = null;
                if ($file = Cache::get($image, false)) {
                } else {
                    $file = InputFile::create($image, 'Notification Image');
                }

                if (mb_strlen($data['text']) > 1020) {
                    $result = $bot->sendPhoto([
                        'chat_id' => $notifiable->telegram_id,
                        'photo' => $file,
                        'caption' => ''
                    ]);

                    Cache::put($image, $this->getBiggestFile($result), 100);

                    if (!($notifiable instanceof Admin)) {
                        TelegramHistory::create([
                            'user_id' => $notifiable->id,
                            'telegram_id' => $notifiable->telegram_id,
                            'status' => TelegramHistory::STATUS_SEND,
                            'message_id' => $result['message_id'],
                            'message' => !empty($data['photo'])
                                ? "[image: {$data['photo']}]\n" . $data['text']
                                : $data['text']
                        ]);
                    }

                    $result = $bot->sendMessage([
                        'chat_id' => $notifiable->telegram_id,
                        'text' => $data['text']
                    ]);
                } else {
                    $result = $bot->sendPhoto([
                        'chat_id' => $notifiable->telegram_id,
                        'photo' => $file,
                        'caption' => $data['text']
                    ]);

                    Log::debug($this->getBiggestFile($result));
                    Log::debug($image);
                    Cache::put($image, $this->getBiggestFile($result), 100);
                }
            } else {
                $result = $bot->sendMessage([
                    'chat_id' => $notifiable->telegram_id,
                    'text' => $data['text']
                ]);
            }


            if (!($notifiable instanceof Admin)) {
                TelegramHistory::create([
                    'user_id' => $notifiable->id,
                    'telegram_id' => $notifiable->telegram_id,
                    'status' => TelegramHistory::STATUS_SEND,
                    'message_id' => $result['message_id'],
                    'message' => !empty($data['photo'])
                        ? "[image: {$data['photo']}]\n" . $data['text']
                        : $data['text']
                ]);
            }
//            if ($result) {
//                Log::debug($result);
//            }
        } catch (TelegramResponseException $e) {
            Log::debug('deliver telegram error.');
            // Ставим юзеру флаг что он отключил телеграмм

            $response = $e->getResponseData();
            Log::debug($response);

            // Юзер залочил телеграм
            if ($response['error_code'] == 403) {
                TelegramHistory::create([
                    'user_id' => $notifiable->id,
                    'telegram_id' => $notifiable->telegram_id,
                    'status' => TelegramHistory::STATUS_BLOCKED,
                    'message' => !empty($data['photo'])
                        ? "[image: {$data['photo']}]\n" . $data['text']
                        : $data['text']
                ]);

                User::where('id', $notifiable->id)->update(['telegram_ban' => true]);
            }
        } catch (\Throwable $e) {
            Log::debug('Telegram unknown exception');
            Log::debug($e);
            TelegramHistory::create([
                'user_id' => $notifiable->id,
                'telegram_id' => $notifiable->telegram_id,
                'status' => TelegramHistory::STATUS_UNKNOWN_ERROR,
                'message' => !empty($data['photo'])
                    ? "[image: {$data['photo']}]\n" . $data['text']
                    : $data['text']
            ]);
        }
    }
}
