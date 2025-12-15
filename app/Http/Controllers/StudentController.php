<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $csvData = array_map('str_getcsv', file($path));
        $headers = array_map('trim', $csvData[0]);
        
        // Remove header row
        unset($csvData[0]);
        
        $imported = 0;
        $errors = [];
        
        foreach ($csvData as $index => $row) {
            $rowNumber = $index + 2; // +2 because index starts at 0 and we removed header
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            try {
                // Map CSV columns to database fields
                $data = [
                    'name' => $row[0] ?? '',
                    'address' => $row[1] ?? '',
                    'email' => $row[2] ?? '',
                    'phone1' => $row[3] ?? '',
                    'phone2' => $row[4] ?? null,
                    'guardian_phone1' => $row[5] ?? '',
                    'current_grade' => $row[6] ?? '',
                    'classroom' => $row[7] ?? '',
                    'id_front' => 'default.jpg',
                    'id_back' => 'default.jpg',
                    'profile_img' => 'default.jpg'
                ];
                
                // Validate required fields
                if (empty($data['name']) || empty($data['email']) || empty($data['address']) || 
                    empty($data['phone1']) || empty($data['guardian_phone1']) || 
                    empty($data['current_grade']) || empty($data['classroom'])) {
                    $errors[] = "Row {$rowNumber}: Missing required fields";
                    continue;
                }
                
                // Check if email already exists
                if (Student::where('email', $data['email'])->exists()) {
                    $errors[] = "Row {$rowNumber}: Email '{$data['email']}' already exists";
                    continue;
                }
                
                Student::create($data);
                $imported++;
                
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }
        
        $message = "{$imported} student(s) imported successfully.";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " row(s) failed.";
        }
        
        return back()->with([
            'success' => $message,
            'import_errors' => $errors
        ]);
    }

    public function exportPdf()
    {
        $students = Student::all();
        $pdf = Pdf::loadView('students.pdf', compact('students'));
        return $pdf->download('students_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}