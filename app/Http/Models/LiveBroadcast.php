<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LiveBroadcast
 *
 * @property int $id
 * @property string|null $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LiveBroadcast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LiveBroadcast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LiveBroadcast query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LiveBroadcast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LiveBroadcast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LiveBroadcast whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LiveBroadcast whereUrl($value)
 * @mixin \Eloquent
 */
class LiveBroadcast extends Model
{
    protected $table = 'live_broadcasts';
}
