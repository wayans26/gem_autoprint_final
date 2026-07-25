<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $connection = 'mysql';
    protected $table = 'users';
    // public $incrementing = true;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'username',
        'full_name',
        'phone',
        'email',
        'password',
        'role_id',
        'status',
        'image',
        'allow_mobile',
    ];

    protected $hidden = [
        'password',
    ];

    public function userExhibitions()
    {
        return $this->hasMany(user_has_exhibitions::class, 'user_id', 'id');
    }

    public function exhibitions()
    {
        return $this->belongsToMany(exhibitions::class, 'user_has_exhibitions', 'user_id', 'exhibitions_id')->withPivot('id');
    }
}
