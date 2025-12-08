<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return view('grades.index', compact('grades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grade_id' => 'required|unique:grades',
            'grade_name' => 'required'
        ]);

        Grade::create($data);

        return back()->with('success', 'Grade added.');
    }

    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);
        $data = $request->validate([
            'grade_name' => 'required'
        ]);

        $grade->update($data);

        return back()->with('success', 'Grade updated.');
    }

    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();
        return back()->with('success', 'Grade deleted.');
    }

    public function search(Request $request)
    {
        $term = $request->input('query');
        $grades = Grade::where('grade_name', 'LIKE', "%$term%")->get();
        return view('grades.index', compact('grades'));
    }
}
