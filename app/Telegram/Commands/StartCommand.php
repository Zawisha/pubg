<?php
/**
 * Created by PhpStorm.
 * User: don3d_000
 * Date: 07.12.2018
 * Time: 1:56
 */

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Traits\Telegram;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    protected $aliases = [];

    /**
     * @var string Command Description
     */
    protected $description = "Start Комманда для начала работы";

    /**
     * @inheritdoc
     */
    public function handle()
    {


//        return true;

//        $upd = $this->getUpdate();

//        debug($upd);

        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.

//            $keyboard = [
//                [['text' => 'Отправить номер телефона',
//                    'request_contact' => true]]
//            ];
//
//            $reply_markup = Keyboard::make([
//                'keyboard' => $keyboard,
//                'resize_keyboard' => true,
//                'one_time_keyboard' => true
//            ]);

//            debug($reply_markup);

//        $response = Telegram::sendMessage([
//            'chat_id' => 'CHAT_ID',
//            'text' => 'Hello World',
//            'reply_markup' => $reply_markup
//        ]);

        try {
//            if ($this->getTelegram()->getAccessToken() !== config('telegram.bots.OnthetopSaveBot.token')) {
//                $this->replyWithMessage([
//                    'text' => __('help_bot.telegram_to_ott_send_code'),
//                ]);
//            } else {
            $this->replyWithMessage([
                'text' => 'For authorization in the bot, send the code from your personal account',
            ]);
//            };
        } catch (\Throwable $er) {
            $this->replyWithMessage([
                'text' => 'For authorization in the bot, send the code from your personal account',
//                'reply_markup' => $reply_markup
            ]);
        }
        // This will update the chat status to typing...
        //$this->replyWithChatAction(['action' => Actions::TYPING]);

        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.
        //$commands = $this->getTelegram()->getCommands();

        // Build the list
//        $response = '';
//        foreach ($commands as $name => $command) {
//            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
//        }

        // Reply with the commands list
//        $this->replyWithMessage(['text' => $response]);

        // Trigger another command dynamically from within this command
        // When you want to chain multiple commands within one or process the request further.
        // The method supports second parameter arguments which you can optionally pass, By default
        // it'll pass the same arguments that are received for this command originally.
//        $this->triggerCommand('subscribe');
    }
}
