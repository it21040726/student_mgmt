<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        $grades = Grade::all();
        $classes = Classroom::all();
        return view('students.index', compact('students', 'grades', 'classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'phone1' => 'required|string',
            'phone2' => 'nullable|string',
            'guardian_phone1' => 'required|string',
            'current_grade' => 'required|string',
            'classroom' => 'required|string',
            'id_front' => 'nullable|image|max:2048',
            'id_back' => 'nullable|image|max:2048',
            'profile_img' => 'nullable|image|max:2048'
        ]);

        // Handle file uploads
        if ($request->hasFile('id_front')) {
            $filename = "student_{$data['email']}_front_" . time() . '.' . $request->id_front->extension();
            $data['id_front'] = $request->id_front->storeAs('student_ids', $filename, 'public');
        }

        if ($request->hasFile('id_back')) {
            $filename = "student_{$data['email']}_back_" . time() . '.' . $request->id_back->extension();
            $data['id_back'] = $request->id_back->storeAs('student_ids', $filename, 'public');
        }

        if ($request->hasFile('profile_img')) {
            $filename = "student_{$data['email']}_profile_" . time() . '.' . $request->profile_img->extension();
            $data['profile_img'] = $request->profile_img->storeAs('student_profiles', $filename, 'public');
        }

        Student::create($data);

        return back()->with('success', 'Student added successfully.');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone1' => 'required|string',
            'phone2' => 'nullable|string',
            'guardian_phone1' => 'required|string',
            'current_grade' => 'required|string',
            'classroom' => 'required|string'
        ]);

        // Handle file uploads
        if ($request->hasFile('id_front')) {
            if ($student->id_front) {
                Storage::disk('public')->delete($student->id_front);
            }
            $data['id_front'] = $request->file('id_front')->store('student_ids', 'public');
        }

        if ($request->hasFile('id_back')) {
            if ($student->id_back) {
                Storage::disk('public')->delete($student->id_back);
            }
            $data['id_back'] = $request->file('id_back')->store('student_ids', 'public');
        }

        if ($request->hasFile('profile_img')) {
            if ($student->profile_img) {
                Storage::disk('public')->delete($student->profile_img);
            }
            $data['profile_img'] = $request->file('profile_img')->store('student_profiles', 'public');
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // Delete associated files
        if ($student->id_front) {
            Storage::disk('public')->delete($student->id_front);
        }
        if ($student->id_back) {
            Storage::disk('public')->delete($student->id_back);
        }
        if ($student->profile_img) {
            Storage::disk('public')->delete($student->profile_img);
        }

        $student->delete();

        return back()->with('success', 'Student deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $grades = Grade::all();
        $classes = Classroom::all();

        $students = Student::where('id', 'LIKE', "%$query%")
            ->orWhere('name', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->orWhere('classroom', 'LIKE', "%$query%")
            ->get();

        return view('students.index', compact('students', 'grades', 'classes'));
    }
}