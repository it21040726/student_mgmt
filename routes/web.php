<?php

use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return redirect()->route('students.create');
});

Route::resource('students', StudentController::class);

