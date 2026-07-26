<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class barcode_config extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'barcode_configs';
    public $incrementing = true;
    protected $primaryKey = 'id';
    // protected $keyType = 'string';
    protected $fillable = [
        'config_name',
        'config_value',
    ];
}
