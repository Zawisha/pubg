<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DoubleGame
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
 * @property string|null $planned_at2
 * @property string|null $planned_at3
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game byMember($memberId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game canceled()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game finished()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game isKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game isMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game newOrStarted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game started()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereIsKing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereLogin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereLogin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereMapName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereMul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame wherePassword2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame wherePassword3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame wherePlannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame wherePlannedAt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame wherePlannedAt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereResultsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereResultsPublished2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereResultsPublished3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereTop1Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereTop2Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereTop3Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereTotalPayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereTotalPayed2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereUseMaxKill($value)
 * @mixin \Eloquent
 * @property int $game_code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game byGamecode($code)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoubleGame whereGameCode($value)
 */
class DoubleGame extends Game
{
    protected $table = 'games';
}
