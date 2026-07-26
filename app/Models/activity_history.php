<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activity_history extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'activity_histories';
    public $incrementing = true;
    protected $primaryKey = 'id';
    // protected $keyType = 'string';
    protected $fillable = [
        'checkin_time',
        'checkin_location',
        'user_id',
        'registration_visitors_id',
    ];
}
