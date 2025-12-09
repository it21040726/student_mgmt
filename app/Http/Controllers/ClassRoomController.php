<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function index()
    {
        $classes = Classroom::all();
        return view('classrooms.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_name' => 'required'
        ]);

        Classroom::create($data);

        return back()->with('success', 'Class added.');
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);
        $data = $request->validate([
            'class_name' => 'required'
        ]);

        $classroom->update($data);

        return back()->with('success', 'Class updated.');
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();
        return back()->with('success', 'Class deleted.');
    }

    public function search(Request $request)
    {
        $term = $request->input('query');
        $classes = Classroom::where('id', 'LIKE', "%$term%")
            ->orWhere('class_name', 'LIKE', "%$term%")
            ->get();
        return view('classrooms.index', compact('classes'));
    }
}
