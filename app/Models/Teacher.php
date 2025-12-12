<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'address',
        'nic',
        'email',
        'phone1',
        'phone2',
        'username',
        'password',
        'subjects',
        'grades',
        'id_front',
        'id_back'
    ];

    protected $hidden = ['password'];
    protected $casts = [
        'subjects' => 'array',
        'grades'   => 'array',
    ];
}
