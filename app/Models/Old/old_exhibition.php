<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class old_exhibition extends Model
{
    //
    protected $connection = 'old_db';
    protected $table = 'tbexhibitions';
    // public $incrementing = true;
    protected $primaryKey = 'idexhibitions';
    protected $keyType = 'string';
    protected $fillable = [
        'idexhibitions',
        'name',
        'path',
        'all_banner',
        'web_own',
        'status',
        'is_show',
        'tanggal',
        'keterangan',
        'event_name',
        'opening_hours',
        'type',
        'custom_tag',
    ];
}
