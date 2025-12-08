<?php

namespace App\Http\Controllers;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function index()
    {
        return view('components.ClassRoom');
    }

    public function create()
    {
        return view('classrooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_ID' => 'required|string|unique:class_rooms,class_ID',
            'class_name' => 'required|string',
        ]);

        ClassRoom::create($request->only('class_ID', 'class_name'));

        return redirect()->route('classroom.index')->with('success', 'Class added successfully.');
    }

    public function show(ClassRoom $classroom)
    {
        // optional - not used here
        return view('classrooms.show', compact('classroom'));
    }

    public function edit(ClassRoom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, ClassRoom $classroom)
    {
        $request->validate([
            'class_ID' => 'required|string|unique:class_rooms,class_ID,' . $classroom->id,
            'class_name' => 'required|string',
        ]);

        $classroom->update($request->only('class_ID', 'class_name'));

        return redirect()->route('classroom.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassRoom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classroom.index')->with('success', 'Class deleted.');
    }
}
