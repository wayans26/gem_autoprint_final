<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class report_file extends Model
{
    const STATUS_PROCESSING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_FAILED = 2;

    protected $connection = 'mysql';
    protected $table = 'report_files';
    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $keyType = 'integer';
    protected $fillable = [
        'user_id',
        'report_type',
        'file_name',
        'path',
        'selected_fields',
        'filters',
        'status',
        'execute_time',
        'exception',
        'completed_at',
    ];
    protected $casts = [
        'selected_fields'   => 'array',
        'filters'           => 'array',
        'status'            => 'integer',
        'execute_time'      => 'decimal:2',
        'completed_at'      => 'datetime',
    ];
}
