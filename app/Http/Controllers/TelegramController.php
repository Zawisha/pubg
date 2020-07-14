<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\TelegramConnected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function webhook()
    {
        app()->setLocale('en');
        $goUpdate = null;

        try {
            // Обрабатываем команды
            $update = Telegram::commandsHandler(true);
            $goUpdate = $update;

            // Если не команда бота
            if (!isset($update['message']['entities'][0]['type'])
                || ($update['message']['entities'][0]['type'] != 'bot_command')) {


                $user = null;
                if (isset($update['message']['text'])
                    && strlen(trim($update['message']['text'])) == 6
                    && is_numeric(trim($update['message']['text']))) {
                    // Ищем пользователя по коду из сообщения
                    $user = User::hasTelegramCode($update['message']['text'])->first();
                }


                if ($user) {
                    // Запоминаем ID юзера, чтобы слать ему уведомления
                    $user->telegram_id = $update['message']['chat']['id'];
                    $user->telegram_ban = false;
                    $user->save();

                    Telegram::sendMessage([
                        'chat_id' => $update['message']['chat']['id'],
                        'text' => __('telegram.notifications') . ' '
                            . $user->name . ' '
                            . __('telegram.notifications_telegram')
                    ]);

                    $user->notify(new TelegramConnected());
                } else {
                    // Не нашли
                    Telegram::sendMessage([
                        'chat_id' => $update['message']['chat']['id'],
                        'text' => __('telegram.no_user')
                    ]);
                }

            }
        } catch (TelegramResponseException $e) {
            Log::debug('deliver telegram error.');

            // Ставим юзеру флаг что он отключил телеграмм
            $response = $e->getResponseData();

//            Log::debug($goUpdate);

            // Юзер залочил телеграм
            if ($response['error_code'] == 403) {

            }
        } catch (\Throwable $ex) {
            Log::debug('unknown telegram error');
            Log::debug($goUpdate);
            Log::debug($ex);
        }

        return 'ok';
    }

    public static function setWebhook()
    {
        $response = Telegram::setWebhook(['url' => route('telegram.webhook')]);

//        return ['repsonse' => $response, 'hookurl' => route('telegram.webhook')];
    }
}