<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_has_exhibitions extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'user_has_exhibitions';
    public $incrementing = true;
    protected $primaryKey = 'id';
    // protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'exhibitions_id',
    ];

    public function exhibitions()
    {
        return $this->belongsTo(exhibitions::class, 'exhibitions_id', 'id');
    }

    public function user()
    {


        return $this->belongsTo(user::class, 'user_id', 'id');
    }
}
