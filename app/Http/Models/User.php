<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use phpDocumentor\Reflection\Types\Float_;
use App\Models\Game;

/**
 * Class User
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string|null $pubg_id
 * @property int $games
 * @property int $kills
 * @property float $balance
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $rank_id
 * @property int $active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $telegram_id
 * @property string|null $comment
 * @property string|null $avatar
 * @property int|null $referral_id
 * @property int $no_mail
 * @property string|null $reflink
 * @property string|null $vk_link
 * @property int $bonus_used
 * @property int $bonus_games
 * @property int $telegram_ban
 * @property int $cbl
 * @property float $kd
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Rank $rank
 * @property-read \App\Models\User|null $referral
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game[] $relatedGames
 * @property-read int|null $related_games_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $subscribers
 * @property-read int|null $subscribers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User hasTelegramCode($code)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBonusGames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBonusUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCbl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereKd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereKills($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNoMail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePubgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereReferralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereReflink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTelegramBan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereVkLink($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pubg_id',
        'games',
        'kills',
        'balance',
        'email_verified_at',
        'rank_id',
        'active',
        'referral_id',
        'vk_link',
        'reflink',
        'bonus_used',
        'bonus_games',
        'cbl',
        'name_cod',
        'cod_id',
        'name_freefire',
        'freefire_id',
        'selected_games'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'selected_games' => 'array',
    ];

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function relatedGames()
    {
        return $this->belongsToMany(Game::class)->withPivot('team', 'bonus', 'gi');
    }

    public function scopeHasTelegramCode($query, $code)
    {
        $map = [
            8 => 0, //0
            5 => 1, //1
            4 => 2, //2
            0 => 3, //3
            9 => 4, //4
            2 => 5, //5
            3 => 6, //6
            7 => 7, //7
            1 => 8, //8
            6 => 9, //9
        ];

        $newCode = '';
        $strLen = strlen($code);
        for ($i = 0; $i < $strLen; $i++) {
            $newCode .= $map[(int)$code[$i]];
        }

        return $query->where('id', $newCode);
    }

    public function referral()
    {
        return $this->belongsTo(self::class, 'referral_id');
    }

    public function subscribers()
    {
        return $this->hasMany(self::class, 'referral_id');
    }

    public function scopeHavePubgId($q)
    {
        return $q->whereNotNull('pubg_id')->orWhereJsonContains('selected_games', (string) Game::GAME_PUBG);
    }

    public function scopeHaveCodId($q)
    {
        return $q->whereNotNull('cod_id')->orWhereJsonContains('selected_games', (string) Game::GAME_CALL_OF_DUTY);
    }

    public function scopeHaveFreeFireId($q)
    {
        return $q->whereNotNull('freefire_id')->orWhereJsonContains('selected_games', (string) Game::GAME_FREEFIRE);
    }
}
