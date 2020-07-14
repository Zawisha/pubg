<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class TelegramHistory
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property int $telegram_id
 * @property int $status
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $message_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory byUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TelegramHistory whereUserId($value)
 * @mixin \Eloquent
 */
class TelegramHistory extends Model
{
    public const STATUS_SEND = 0;
    public const STATUS_BLOCKED = 1;
    public const STATUS_UNKNOWN_ERROR = 2;

    public const STATUS_NAMES = [
        self::STATUS_SEND => 'admin.telegram.send',
        self::STATUS_BLOCKED => 'admin.telegram.blocked',
        self::STATUS_UNKNOWN_ERROR => 'admin.telegram.unknown_error'
    ];

    protected $table = 'telegram_history';

    protected $fillable = [
        'user_id',
        'telegram_id',
        'status',
        'message',
        'message_id'
    ];

    public static function getStatusNames()
    {
        return collect(self::STATUS_NAMES)->map(function ($item) {
            return __($item);
        })->toArray();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeByUser($q, $userId)
    {
        return $q->where('user_id', $userId);
    }

    /**
     * @param $messagesId
     */
    public static function clearUserMessages($like, $date)
    {

        $bot = Telegram::bot();
        foreach (self::where('message', 'like', '%' . $like . '%')
                     ->where('created_at', '>', $date)
                     ->get() as $msg) {
            try {
                echo 'deleting ' . $msg->id . ' ';
                $bot->deleteMessage(['chat_id' => $msg->telegram_id, 'message_id' => $msg->message_id]);
                echo 'success';
            } catch (\Throwable $ex) {
                echo 'fail';
            }
        };
    }

    public function removeFromTelegram()
    {
        $bot = Telegram::bot();
        print_r($bot->deleteMessage(['chat_id' => $this->telegram_id, 'message_id' => $this->message_id]));
    }
}