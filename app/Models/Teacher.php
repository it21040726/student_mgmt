<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'teacher_id',
        'name',
        'address',
        'nic',
        'email',
        'phone1',
        'phone2',
        'username',
        'password',
        'subjects',
        'grades'
    ];

    protected $hidden = ['password'];
}
