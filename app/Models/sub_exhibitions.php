<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sub_exhibitions extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'sub_exhibitions';
    public $incrementing = true;
    protected $primaryKey = 'id';
    // protected $keyType = 'string';
    protected $fillable = [
        'exhibitions_id',
        'code',
        'name',
        'file_banner',
        'status',
    ];
}
