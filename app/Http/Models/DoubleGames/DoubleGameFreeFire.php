<?php

namespace App\Models\DoubleGames;

use App\Models\DoubleGame;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Games\GameFreeFire
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
 * @property int $game_code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game byGamecode($code)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game byMember($memberId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game canceled()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game finished()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game isKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game isMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game newOrStarted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game started()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereGameCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereIsKing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereLogin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereLogin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereMapName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereMul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire wherePassword2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire wherePassword3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire wherePlannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire wherePlannedAt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire wherePlannedAt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereResultsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereResultsPublished2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereResultsPublished3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereTop1Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereTop2Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereTop3Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereTotalPayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereTotalPayed2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameFreeFire whereUseMaxKill($value)
 * @mixin \Eloquent
 */
class DoubleGameFreeFire extends DoubleGame
{
    protected $table = 'games';
}
