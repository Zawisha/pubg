<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property int $game_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'name',
        'game_id'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
