<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $fillable = [
        'Course ID',
        'Course Name',
        'Course Code',
        'Course Category',
    ];
}
