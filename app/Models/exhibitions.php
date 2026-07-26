<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class exhibitions extends Model
{
    //
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'exhibitions';
    // public $incrementing = true;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = [
        'code',
        'banner_file',
        'all_banner',
        'name',
        'full_name',
        'location',
        'date',
        'team',
        'opening_hours',
        'host',
        'type',
        'page',
        'path',
        'status',
    ];

    public function user_has_exhibitions()
    {
        return $this->hasMany(user_has_exhibitions::class, 'exhibitions_id', 'id');
    }

    public function user()
    {
        return $this->belongsToMany(user::class, 'user_has_exhibitions', 'exhibition_id', 'user_id')->withPivot('id');
    }
}
