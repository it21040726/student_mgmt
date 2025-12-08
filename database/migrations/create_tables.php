<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_id')->unique();
            $table->string('name');
            $table->string('address');
            $table->string('nic');
            $table->string('email')->unique();
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->string('subjects')->nullable(); 
            $table->string('grades')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('name');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->string('guardian_phone1');
            $table->string('guardian_phone2')->nullable();
            $table->string('current_grade');
            $table->string('classroom');
            $table->timestamps();
        });
        
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_id')->unique();
            $table->string('course_name');
            $table->string('course_code')->unique();
            $table->string('course_category');
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code')->unique();
            $table->string('subject_name');
            $table->timestamps();
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('grade_id')->unique();
            $table->string('grade_name');
            $table->timestamps();
        });

        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('class_id')->unique();
            $table->string('class_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('students');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('classes');
    }
};


