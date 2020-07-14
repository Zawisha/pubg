<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminAuthHistory
 *
 * @property int $id
 * @property int $admin_id
 * @property string|null $ip
 * @property string|null $agent
 * @property \Illuminate\Support\Carbon $stamp
 * @property-read \App\Models\Admin $admin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory byAdmin($admin_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminAuthHistory whereStamp($value)
 * @mixin \Eloquent
 */
class AdminAuthHistory extends Model
{
    public $timestamps = false;
    protected $table = 'admin_auth_history';

    protected $fillable = ['stamp', 'ip', 'admin_id', 'agent'];

    protected $dates = ['stamp'];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function scopeByAdmin($query, $admin_id)
    {
        return $query->where('admin_id', $admin_id);
    }
}
