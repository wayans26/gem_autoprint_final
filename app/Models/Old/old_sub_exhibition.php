<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class old_sub_exhibition extends Model
{
    //
    protected $connection = 'old_db';
    protected $table = 'tbsubexhibitions';
    // public $incrementing = true;
    protected $primaryKey = 'idsubexhibitions';
    protected $keyType = 'string';
    protected $fillable = [
        'idexhibitions',
        'idsubexhibitions',
        'nama',
        'path',
    ];
}
