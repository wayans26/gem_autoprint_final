<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class countries extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'countries';
    public $incrementing = true;
    protected $primaryKey = 'id';
    // protected $keyType = 'string';
    protected $fillable = [
        'code',
        'country_name',
        'dial_code',
    ];
}
