<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\String_;

/**
 * Class Blogger
 * @package App\Models
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $vk
 * @property int $processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger processed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Blogger whereVk($value)
 * @mixin \Eloquent
 */
class Blogger extends Model
{
    protected $table = 'bloggers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'vk',
    ];

    public function scopeNew($q)
    {
        return $q->where('processed', false);
    }

    public function scopeProcessed($q)
    {
        return $q->where('processed', true);
    }
}