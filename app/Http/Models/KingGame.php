<?php
/**
 * Created by PhpStorm.
 * User: don3d_000
 * Date: 24.10.2019
 * Time: 23:31
 */

namespace App\Models;


use App\Models\Game;

/**
 * App\Models\KingGame
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game byMember($memberId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game canceled()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game finished()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game isKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game isMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game newOrStarted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notKing()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game notMultiple()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game started()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereIsKing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereLogin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereLogin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereMapName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereMul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame wherePassword2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame wherePassword3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame wherePlannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame wherePlannedAt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame wherePlannedAt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereResultsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereResultsPublished2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereResultsPublished3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereTop1Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereTop2Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereTop3Prize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereTotalPayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereTotalPayed2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\KingGame whereUseMaxKill($value)
 * @mixin \Eloquent
 */
class KingGame extends Game
{
    protected $table = 'games';
}