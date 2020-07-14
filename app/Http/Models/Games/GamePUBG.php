<?php

namespace App\Models\Games;

use App\Models\Game;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Games\GamePUBG
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game newOrStarted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game started()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereGameCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereIsKing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereLogin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereLogin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereMapName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereMul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG wherePassword2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG wherePassword3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG wherePlannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG wherePlannedAt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG wherePlannedAt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereResultsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereResultsPublished2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereResultsPublished3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereTop1Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereTop2Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereTop3Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereTotalPayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereTotalPayed2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Games\GamePUBG whereUseMaxKill($value)
 * @mixin \Eloquent
 */
class GamePUBG extends Game
{
    protected $table = 'games';
}
