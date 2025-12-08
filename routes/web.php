<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('students.create');
});

Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
Route::resource('students', StudentController::class)->except(['edit']);
Route::get('/student', [StudentController::class, 'index'])->name('student.index');
Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher.index');
Route::get('/courses', [CoursesController::class, 'index'])->name('courses.index');
Route::get('/grade', [GradeController::class, 'index'])->name('grade.index');
Route::get('/subjects', [SubjectsController::class, 'index'])->name('subjects.index');
Route::get('/classroom', [ClassRoomController::class, 'index'])->name('classroom.index');
