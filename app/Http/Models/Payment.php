<?php

namespace App\Models;

use App\Traits\PaymentTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int|null $transaction_id
 * @property int|null $game_id
 * @property float $amount
 * @property string $merchant
 * @property string|null $merchant_id
 * @property int $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game|null $game
 * @property-read \App\Models\Transaction|null $transaction
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereMerchant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereUserId($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use PaymentTrait;

    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'merchant',
        'merchant_id',
        'status',
        'comment',
    ];

    public const STATUS_NEW = 0;
    public const STATUS_WAITING = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_ERROR = 3;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
