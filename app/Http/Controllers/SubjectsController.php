<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_name' => 'required',
            'subject_code' => 'required'
        ]);

        Subject::create($data);

        return back()->with('success', 'Subject added.');
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $data = $request->validate([
            'subject_name' => 'required',
            'subject_code' => 'required'
        ]);

        $subject->update($data);

        return back()->with('success', 'Subject updated.');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return back()->with('success', 'Subject deleted.');
    }

    public function search(Request $request)
    {
        $term = $request->input('query');
        $subjects = Subject::where('id', 'LIKE', "%$term%")
        ->orWhere('subject_code', 'LIKE', "%$term%")
        ->orWhere('subject_name', 'LIKE', "%$term%")
        ->get();
        return view('subjects.index', compact('subjects'));
    }
}
