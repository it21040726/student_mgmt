<?php
namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        $grades = Grade::all();
        $subjects = Subject::all();
        return view('teachers.index', compact('teachers', 'subjects', 'grades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required',
            'address'   => 'required',
            'nic'       => 'required',
            'email'     => 'required|email',
            'phone1'    => 'required',
            'phone2'    => 'nullable',
            'username'  => 'required',
            'password'  => 'required',
            'subjects'  => 'required',
            'grades'    => 'required',
            'id_front'  => 'required|image|max:2048',
            'id_back'   => 'required|image|max:2048'
        ]);

        $id_front_path = null;
        $id_back_path = null;

        if ($request->hasFile('id_front')) {
            $filename = "teacher_{$data['username']}_front_" . time() . '.' . $request->id_front->extension();
            $id_front_path = $request->id_front->storeAs('id_cards', $filename, 'public');
        }

        if ($request->hasFile('id_back')) {
            $filename = "teacher_{$data['username']}_back_" . time() . '.' . $request->id_back->extension();
            $id_back_path = $request->id_back->storeAs('id_cards', $filename, 'public');
        }

        // Hash password
        $data['password'] = bcrypt($data['password']);

        // Convert arrays to JSON for DB storage
        $data['subjects'] = json_encode($data['subjects']);
        $data['grades']   = json_encode($data['grades']);
        $data['id_front'] = $id_front_path;
        $data['id_back'] = $id_back_path;

        Teacher::create($data);

        return back()->with('success', 'Teacher added successfully.');
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'nic' => 'required',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone1' => 'required',
            'phone2' => 'nullable',
            'username' => 'required|unique:teachers,username,' . $teacher->id,
            'subjects' => 'required|array',
            'grades' => 'required|array'
        ]);

        if ($request->hasFile('id_front')) {
            if ($teacher->id_front) {
                Storage::disk('public')->delete($teacher->id_front);
            }
            $data['id_front'] = $request->file('id_front')->store('id_cards', 'public');
        }

        if ($request->hasFile('id_back')) {
            if ($teacher->id_back) {
                Storage::disk('public')->delete($teacher->id_back);
            }
            $data['id_back'] = $request->file('id_back')->store('id_cards', 'public');
        }

        $subjects = $request->input('subjects');
        $grades = $request->input('grades');

        $data['subjects'] = json_encode($subjects);
        $data['grades'] = json_encode($grades);

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
        $query = $request->input('query'); 
        
        $teachers = Teacher::where('id', 'LIKE', "%$query%")
            ->orWhere('name', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->get();

        return view('teachers.index', compact('teachers'));
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
        
        unset($csvData[0]);
        
        $imported = 0;
        $errors = [];
        
        foreach ($csvData as $index => $row) {
            $rowNumber = $index + 2;
            
            if (empty(array_filter($row))) {
                continue;
            }
            
            try {
                $subjectsArray = !empty($row[6]) ? array_map('trim', explode(',', $row[6])) : [];
                $gradesArray = !empty($row[7]) ? array_map('trim', explode(',', $row[7])) : [];
                
                $data = [
                    'name' => $row[0] ?? '',
                    'address' => $row[1] ?? '',
                    'nic' => $row[2] ?? '',
                    'email' => $row[3] ?? '',
                    'phone1' => $row[4] ?? '',
                    'phone2' => $row[5] ?? null,
                    'subjects' => json_encode($subjectsArray),
                    'grades' => json_encode($gradesArray),
                    'username' => $row[8] ?? '',
                    'password' => bcrypt('12345678'),
                    'id_front' => 'default.jpg',
                    'id_back' => 'default.jpg'
                ];
                
                if (empty($data['name']) || empty($data['address']) || empty($data['nic']) ||
                    empty($data['email']) || empty($data['phone1']) || empty($data['username']) ||
                    empty($subjectsArray) || empty($gradesArray)) {
                    $errors[] = "Row {$rowNumber}: Missing required fields";
                    continue;
                }
                
                if (Teacher::where('email', $data['email'])->exists()) {
                    $errors[] = "Row {$rowNumber}: Email '{$data['email']}' already exists";
                    continue;
                }
                
                if (Teacher::where('username', $data['username'])->exists()) {
                    $errors[] = "Row {$rowNumber}: Username '{$data['username']}' already exists";
                    continue;
                }
                
                Teacher::create($data);
                $imported++;
                
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }
        
        $message = "{$imported} teacher(s) imported successfully.";
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
        $teachers = Teacher::all();
        $pdf = Pdf::loadView('teachers.pdf', compact('teachers'));
        return $pdf->download('teachers_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}