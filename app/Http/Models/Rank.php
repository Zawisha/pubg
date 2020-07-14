<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class Rank
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string|null $requirements
 * @property string|null $description
 * @property int $rq_battles
 * @property int $rq_kills
 * @property int $cashback
 * @property int $kill_reward
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $requirements_en
 * @property string|null $description_en
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereCashback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereKillReward($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereRequirementsEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereRqBattles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereRqKills($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float $kill_reward2
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereKillReward2($value)
 */
class Rank extends Model
{
    protected $table = 'ranks';

    protected $fillable = [
        'name',
        'requirements',
        'description',
        'rq_battles',
        'rq_kills',
        'cashback',
        'kill_reward',
        'image',
    ];
}
