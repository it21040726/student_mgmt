<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('teacher')->get();
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_code' => 'required|unique:subjects',
            'subject_name' => 'required'
        ]);

        Subject::create($data);

        return back()->with('success', 'Subject added.');
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'subject_name' => 'required'
        ]);

        $subject->update($data);

        return back()->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Subject deleted.');
    }

    public function search(Request $request)
    {
        $term = $request->query('q');

        $subjects = Subject::where('subject_code', 'LIKE', "%$term%")
            ->orWhere('subject_name', 'LIKE', "%$term%")
            ->get();

        return view('subjects.index', compact('subjects'));
    }
}
