<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Content
 *
 * @property int $id
 * @property string $name
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $content_en
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereContentEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Content extends Model
{
    protected $table = 'content';
}
