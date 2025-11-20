@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Student Profile</h3>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        ➕ Add Student
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-container">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>course</th>
                <th style="width: 150px">Actions</th>
            </tr>
        </thead>

        <tbody>
        @foreach($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->DOB }}</td>
                <td>{{ ucfirst($student->gender) }}</td>
                <td>{{ $student->course }}</td>
                <td>
                    <div class="d-flex gap-2">

                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this student?')">
                                🗑️ Delete
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
</div>

@endsection
