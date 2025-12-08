<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_id',
        'course_name',
        'course_code',
        'course_category'
    ];
}
