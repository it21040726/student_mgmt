<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|unique:courses',
            'course_name' => 'required',
            'course_code' => 'required',
            'course_category' => 'required',
        ]);

        Course::create($data);

        return back()->with('success', 'Course added.');
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $data = $request->validate([
            'course_name' => 'required',
            'course_code' => 'required',
            'course_category' => 'required',
        ]);

        $course->update($data);

        return back()->with('success', 'Course updated.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return back()->with('success', 'Course deleted.');
    }

    public function search(Request $request)
    {
        $term = $request->input('query');

        $courses = Course::where('course_id', 'LIKE', "%$term%")
            ->orWhere('course_name', 'LIKE', "%$term%")
            ->orWhere('course_code', 'LIKE', "%$term%")
            ->get();

        return view('courses.index', compact('courses'));
    }

}
