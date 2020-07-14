<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;

/**
 * Class Mailing
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string|null $message
 * @property string|null $short_message
 * @property string|null $image
 * @property int $to_email
 * @property int $to_bot
 * @property int $to_lk
 * @property \Illuminate\Support\Carbon|null $mailed_at
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $ranks
 * @property int $min_balance
 * @property int $membership_type
 * @property \Illuminate\Support\Carbon|null $created_from
 * @property \Illuminate\Support\Carbon|null $created_to
 * @property int $max_balance
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game[] $games
 * @property-read int|null $games_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereCreatedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereCreatedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereMailedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereMaxBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereMembershipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereMinBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereRanks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereShortMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereToBot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereToEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereToLk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mailing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mailing extends Model
{
    public const STATUS_MAILED = 2;

    protected $table = 'mailings';

    protected $fillable = [
        'name',
        'message',
        'short_message',
        'image',
        'to_email',
        'to_bot',
        'to_lk',
        'mailed_at',
        'status',
        'ranks',
        'min_balance',
        'membership_type',
        'games',
        'created_from',
        'created_to'
    ];

    public const MEMBERSHIP_IGNORE = 0;
    public const MEMBERSHIP_MEMBER = 1;
    public const MEMBERSHIP_NOT_MEMBER = 2;

    protected $dates = [
        'mailed_at',
        'created_from',
        'created_to'
    ];

    protected $casts = [
        'ranks' => 'array',
        'games' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }
}
