<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property int $type
 * @property int $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereUserId($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    protected $table = 'transactions';

    public const STATUS_NORMAL = 0;
    public const STATUS_CANCELED = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_MANUAL_REFUND = 3;

    public const WITHDRAW_STATUS_NAMES = [
        self::STATUS_NORMAL => 'admin.transactions.statuses.normal',
        self::STATUS_CANCELED => 'admin.transactions.statuses.canceled',
        self::STATUS_CONFIRMED => 'admin.transactions.statuses.confirmed',
        self::STATUS_MANUAL_REFUND => 'admin.transactions.statuses.manual_refund'
    ];

    public const TYPE_PAYMENT = 1;
    public const TYPE_FEE = 2;
    public const TYPE_WITHDRAW = 3;
    public const TYPE_GAME_PAYMENT = 4;
    public const TYPE_GAME_RETURN = 5;
    public const TYPE_KILL = 6;
    public const TYPE_REFUND = 7;
    public const TYPE_ADMIN = 8;
    public const TYPE_EPS_CANCEL = 9;
    public const TYPE_TOP1_PAYMENT = 10;
    public const TYPE_TOP2_PAYMENT = 11;
    public const TYPE_TOP3_PAYMENT = 12;
    public const TYPE_GAME_FEE = 14;
    public const TYPE_WITHDRAW_PAYPAL = 31;

    public const TYPE_NAMES = [
        self::TYPE_PAYMENT => 'admin.transactions.types.payment',
        self::TYPE_FEE => 'admin.transactions.types.fee',
        self::TYPE_WITHDRAW => 'admin.transactions.types.withdraw',
        self::TYPE_WITHDRAW_PAYPAL => 'admin.transactions.types.withdraw_paypall',
        self::TYPE_GAME_PAYMENT => 'admin.transactions.types.game_payment',
        self::TYPE_GAME_RETURN => 'admin.transactions.types.game_return',
        self::TYPE_KILL => 'admin.transactions.types.kill',
        self::TYPE_REFUND => 'admin.transactions.types.refund',
        self::TYPE_ADMIN => 'admin.transactions.types.admin',
        self::TYPE_EPS_CANCEL => 'admin.transactions.types.eps_cancel',
        self::TYPE_TOP1_PAYMENT => 'admin.transactions.types.top1_payment',
        self::TYPE_TOP2_PAYMENT => 'admin.transactions.types.top2_payment',
        self::TYPE_TOP3_PAYMENT => 'admin.transactions.types.top3_payment',
        self::TYPE_GAME_FEE => 'admin.transactions.types.game_fee',
    ];

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'status',
        'comment',
    ];

    public static function getTypeNames()
    {
        return collect(self::TYPE_NAMES)->map(function ($item) {
            return __($item);
        })->toArray();
    }

    public function getTypeName()
    {
        return __(self::TYPE_NAMES[$this->type]);
    }

    public function getStatusText()
    {
        if ($this->type != self::TYPE_WITHDRAW
            && $this->type != self::TYPE_WITHDRAW_PAYPAL) {
            return '-';
        }

        return __(self::WITHDRAW_STATUS_NAMES[$this->status]);
    }

    public function getCommentsText()
    {
        if ($this->type == self::TYPE_ADMIN) {
            return __('admin.transactions.change') . ' ' . json_decode($this->comment)->admin_name;
        }

        if ($this->type == self::TYPE_FEE || $this->type == self::TYPE_GAME_FEE) {
//            return $this->comment;
            try {
                return __('admin.transactions.cashback') . ' ' . User::find(explode(':', $this->comment)[1])->name;
            } catch (\Throwable $er) {
                return $this->comment;
            }
        }

        return ' - ';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
