<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class exhibitions extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'exhibitions';
    // public $incrementing = true;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = [
        'code',
        'name',
        'tanggal',
        'keterangan',
        'path',
        'all_banner',
        'web_own',
        'status',
        'is_show',
        'event_name',
        'type',
        'page_name',
        'opening_hours',
    ];

    public function user_has_exhibitions()
    {
        return $this->hasMany(user_has_exhibitions::class, 'exhibition_id', 'id');
    }
}
