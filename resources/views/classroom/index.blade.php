@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Student Profile</h3>
    </div>
    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">

            <form action="{{ route('students.search') }}" method="GET" class="d-flex w-50">
                <input type="text" name="query" value="{{ request('query') }}"
                       class="form-control me-2" placeholder="Search by ID, Name or Email">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
            <a href="{{ route('students.create') }}" class="btn btn-info text-white">
                + Add Student
            </a>
        </div>
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

                            <button
                                class="btn btn-warning btn-sm editBtn"
                                data-id="{{ $student->id }}"
                                data-first="{{ $student->first_name }}"
                                data-last="{{ $student->last_name }}"
                                data-email="{{ $student->email }}"
                                data-phone="{{ $student->phone }}"
                                data-dob="{{ $student->DOB }}"
                                data-gender="{{ $student->gender }}"
                                data-course="{{ $student->course }}"
                            >
                                ✏️ Edit
                            </button>

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

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Student</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <input type="hidden" id="edit_id" name="id">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input id="edit_first" name="first_name" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input id="edit_last" name="last_name" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input id="edit_email" name="email" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input id="edit_phone" name="phone" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">DOB</label>
                                <input type="date" id="edit_dob" name="DOB" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select id="edit_gender" name="gender" class="form-select">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Course</label>
                                <input id="edit_course" name="course" class="form-control">
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary px-4">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const editButtons = document.querySelectorAll('.editBtn');
            const modal = new bootstrap.Modal(document.getElementById('editModal'));

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {

                    document.getElementById('edit_id').value = this.dataset.id;
                    document.getElementById('edit_first').value = this.dataset.first;
                    document.getElementById('edit_last').value = this.dataset.last;
                    document.getElementById('edit_email').value = this.dataset.email;
                    document.getElementById('edit_phone').value = this.dataset.phone;
                    document.getElementById('edit_dob').value = this.dataset.dob;
                    document.getElementById('edit_gender').value = this.dataset.gender;
                    document.getElementById('edit_course').value = this.dataset.course;


                    document.getElementById('editForm').action =
                        "/students/" + this.dataset.id;

                    modal.show();
                });
            });
        });
    </script>
@endsection
