<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AdminAuth;


Route::get('/login', [AdminController::class, 'loginPage'])->name('admin.login');
Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');

Route::middleware([AdminAuth::class])->group(function () {
    // Admin Routes
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Dashboard Routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    });

    // Student Routes
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('students.index');
        Route::get('/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/', [StudentController::class, 'store'])->name('students.store');
        Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/{id}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::get('/search', [StudentController::class, 'search'])->name('students.search');
    });

    // Teacher Routes
    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('/create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/', [TeacherController::class, 'store'])->name('teachers.store');
        Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('/{id}', [TeacherController::class, 'update'])->name('teachers.update');
        Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
        Route::get('/search', [TeacherController::class, 'search'])->name('teachers.search');
    });

    // Course Routes
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/{id}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/search', [CourseController::class, 'search'])->name('courses.search');
    });

    // Class Routes
    Route::prefix('classes')->group(function () {
        Route::get('/', [ClassRoomController::class, 'index'])->name('classes.index');
        Route::get('/create', [ClassRoomController::class, 'create'])->name('classes.create');
        Route::post('/', [ClassRoomController::class, 'store'])->name('classes.store');
        Route::get('/{id}/edit', [ClassRoomController::class, 'edit'])->name('classes.edit');
        Route::put('/{id}', [ClassRoomController::class, 'update'])->name('classes.update');
        Route::delete('/{id}', [ClassRoomController::class, 'destroy'])->name('classes.destroy');
        Route::get('/search', [ClassRoomController::class, 'search'])->name('classes.search');
    });

    // Grade Routes
    Route::prefix('grades')->group(function () {
        Route::get('/', [GradeController::class, 'index'])->name('grades.index');
        Route::get('/create', [GradeController::class, 'create'])->name('grades.create');
        Route::post('/', [GradeController::class, 'store'])->name('grades.store');
        Route::get('/{id}/edit', [GradeController::class, 'edit'])->name('grades.edit');
        Route::put('/{id}', [GradeController::class, 'update'])->name('grades.update');
        Route::delete('/{id}', [GradeController::class, 'destroy'])->name('grades.destroy');
        Route::get('/search', [GradeController::class, 'search'])->name('grades.search');
    });

    // Subject Routes
    Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectsController::class, 'index'])->name('subjects.index');
        Route::get('/create', [SubjectsController::class, 'create'])->name('subjects.create');
        Route::post('/', [SubjectsController::class, 'store'])->name('subjects.store');
        Route::get('/{id}/edit', [SubjectsController::class, 'edit'])->name('subjects.edit');
        Route::put('/{id}', [SubjectsController::class, 'update'])->name('subjects.update');
        Route::delete('/{id}', [SubjectsController::class, 'destroy'])->name('subjects.destroy');
        Route::get('/search', [SubjectsController::class, 'search'])->name('subjects.search');
    });
});

