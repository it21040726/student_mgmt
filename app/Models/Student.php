<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'address',
        'email',
        'phone1',
        'phone2',
        'guardian_phone1',
        'current_grade',
        'classroom',
        'id_front',
        'id_back',
        'profile_img'
    ];
}