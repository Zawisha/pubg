<?php

namespace App\Models\DoubleGames;

use App\Models\DoubleGame;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Games\GameCallOfDuty
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game newOrStarted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game started()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereGameCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereIsKing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereLogin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereLogin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereMapName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereMul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty wherePassword2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty wherePassword3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty wherePlannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty wherePlannedAt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty wherePlannedAt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereResultsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereResultsPublished2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereResultsPublished3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereTop1Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereTop2Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereTop3Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereTotalPayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereTotalPayed2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GameCallOfDuty whereUseMaxKill($value)
 * @mixin \Eloquent
 */
class DoubleGameCallOfDuty extends DoubleGame
{
    protected $table = 'games';
}
