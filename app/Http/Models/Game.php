<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $planned_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property float $price
 * @property string|null $login
 * @property string|null $password
 * @property string|null $image
 * @property int $status
 * @property int $type
 * @property string|null $comment
 * @property string|null $map_name
 * @property int $max_players
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $face
 * @property int $results_published
 * @property int $top1_prize
 * @property int $is_king
 * @property \Illuminate\Support\Carbon|null $planned_at2
 * @property \Illuminate\Support\Carbon|null $planned_at3
 * @property int $results_published2
 * @property int $results_published3
 * @property float $top2_prize
 * @property float $top3_prize
 * @property float $total_payed
 * @property string|null $login2
 * @property string|null $password2
 * @property string|null $login3
 * @property string|null $password3
 * @property int $use_max_kill
 * @property int $mul
 * @property float $total_payed2
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game byMember($memberId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game canceled()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game finished()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game isKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game isMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game newOrStarted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game started()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereIsKing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereLogin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereLogin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereMapName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereMul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game wherePassword2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game wherePassword3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game wherePlannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game wherePlannedAt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game wherePlannedAt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereResultsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereResultsPublished2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereResultsPublished3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereTop1Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereTop2Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereTop3Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereTotalPayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereTotalPayed2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereUseMaxKill($value)
 * @mixin \Eloquent
 * @property int $game_code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game byGamecode($code)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereGameCode($value)
 */
class Game extends Model
{
    public const STATUS_NEW = 0;
    public const STATUS_STARTED = 1;
    public const STATUS_FINISHED = 2;
    public const STATUS_CANCELED = 3;

    public const STATUS_NAMES = [
        self::STATUS_NEW => 'admin.games.statuses.new',
        self::STATUS_STARTED => 'admin.games.statuses.started',
        self::STATUS_FINISHED => 'admin.games.statuses.finished',
//        self::STATUS_CANCELED => 'Отменен',
    ];

    public const TYPE_SOLO = 0;
    public const TYPE_DUO = 1;
    public const TYPE_SQUAD = 2;

    public const FACE_FIRST = 0;
    public const FACE_THIRD = 3;

    public const GAME_PUBG = 0;
    public const GAME_FREEFIRE = 1;
    public const GAME_CALL_OF_DUTY = 2;

    public const TYPE_NAMES = [
        self::TYPE_SOLO => 'admin.games.types.solo',
        self::TYPE_DUO => 'admin.games.types.duo',
        self::TYPE_SQUAD => 'admin.games.types.squad',
    ];

    public const FACE_NAMES = [
        self::FACE_FIRST => 'admin.games.faces.first',
        self::FACE_THIRD => 'admin.games.faces.third'
    ];

    public const GAME_NAMES = [
        self::GAME_PUBG => 'PUBG',
        self::GAME_FREEFIRE => 'FREE FIRE',
        self::GAME_CALL_OF_DUTY => 'CALL OF DUTY'
    ];

    protected $dates = [
        'planned_at',
        'started_at',
        'finished_at',
        'planned_at2',
        'planned_at3',
    ];

    protected $fillable = [
        'id',
        'name',
        'planned_at',
        'planned_at2',
        'planned_at3',
        'started_at',
        'finished_at',
        'price',
        'login',
        'password',
        'image',
        'status',
        'type',
        'comment',
        'map_name',
        'max_players',
        'is_king',
        'total_payed',
        'login2',
        'password2',
        'login3',
        'password3',
        'use_max_kill'
    ];

    protected $hidden = [
        'total_payed',
        'total_payed2'
    ];

    public static function getStatusNames()
    {
        return collect(self::STATUS_NAMES)->map(function ($item) {
            return __($item);
        })->toArray();
    }

    public static function getTypeNames()
    {
        return collect(self::TYPE_NAMES)->map(function ($item) {
            return __($item);
        })->toArray();
    }

    public static function getFaceNames()
    {
        return collect(self::FACE_NAMES)->map(function ($item) {
            return __($item);
        })->toArray();
    }

    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeNewOrStarted($query)
    {
        return $query->whereIn('status', [self::STATUS_NEW, self::STATUS_STARTED]);
    }

    public function scopeFinished($query)
    {
        return $query->where('status', self::STATUS_FINISHED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', self::STATUS_CANCELED);
    }

    public function scopeStarted($query)
    {
        return $query->where('status', self::STATUS_STARTED);
    }

    public function getTypeName()
    {
        return self::TYPE_NAMES[$this->type];
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'game_user', 'game_id', 'user_id')
            ->withPivot(['team', 'kills', 'visit', 'kills1', 'kills2', 'kills3', 'color', 'bonus', 'gi'])
            ->orderBy('gi')
            ->orderBy('team');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'game_id');
    }

    public function getTeamSize()
    {
        if ($this->type == self::TYPE_SQUAD) {
            return 4;
        }

        if ($this->type == self::TYPE_DUO) {
            return 2;
        }

        if ($this->type == self::TYPE_SOLO) {
            return 1;
        }
    }

    public function getTeamsCount()
    {
        return ceil($this->max_players / $this->getTeamSize());
    }

    public function getTeamsArray($filler = 0)
    {
        $teams = [];

        for ($gi = 0; $gi < $this->mul; $gi++) {
            $teams[$gi] = [];

            for ($i = 1; $i <= $this->getTeamsCount(); $i++) {
                $teams[$gi]['team ' . $i] = $filler;
            }
        }

        return $teams;
    }

    public function scopeByMember($q, $memberId)
    {
        return $q->whereHas('members', function ($query) use ($memberId) {
            return $query->where('id', $memberId);
        });
    }

    public function scopeIsKing($q)
    {
        return $q->where('is_king', true);
    }

    public function scopeNotKing($q)
    {
        return $q->where('is_king', false);
    }

    public function scopeIsMultiple($q)
    {
        return $q->where('mul', '>', 1);
    }

    public function scopeNotMultiple($q)
    {
        return $q->where('mul', 1);
    }

    public function isDouble()
    {
        return $this->mul == 2;
    }

    public function scopeByGamecode($q, $code)
    {
        return $q->where('game_code', $code);
    }

    public function getGameName()
    {
        return self::GAME_NAMES[$this->game_code];
    }

    public function getGameNameNotificationString()
    {
        return $this->getGameName() . "\n" .
            "-----\n";
    }
}
