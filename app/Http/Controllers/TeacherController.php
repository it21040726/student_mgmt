<?php
namespace App\Http\Controllers;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers.index', compact('teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|unique:teachers',
            'name' => 'required',
            'address' => 'required',
            'nic' => 'required',
            'email' => 'required|email',
            'phone1' => 'required',
            'phone2' => 'nullable',
            'username' => 'required',
            'password' => 'required',
            'subjects' => 'required',
            'grades' => 'required'
        ]);

        $data['password'] = bcrypt($data['password']);
        Teacher::create($data);

        return back()->with('success', 'Teacher added successfully.');
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $data = $request->validate([
            'teacher_id' => 'required|unique:teachers,teacher_id,' . $teacher->id,
            'name' => 'required',
            'address' => 'required',
            'nic' => 'required',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone1' => 'required',
            'phone2' => 'nullable',
            'username' => 'required|unique:teachers,username,' . $teacher->id,
            'subjects' => 'required',
            'grades' => 'required'
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $teacher->update($data);

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return back()->with('success', 'Teacher deleted.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // FIX: was $request->query
        
        $teachers = Teacher::where('teacher_id', 'LIKE', "%$query%")
            ->orWhere('name', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->get();

        return view('teachers.index', compact('teachers'));
    }
}