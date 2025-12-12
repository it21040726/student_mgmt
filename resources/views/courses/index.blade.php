@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Course Management</h3>
</div>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <form action="{{ route('courses.search') }}" method="GET" class="d-flex w-50">
            <input type="text" name="query" value="{{ request('query') }}"
                   class="form-control me-2" placeholder="Search by Course Name or Code">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Course
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Validation Errors:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="table-container">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Course Code</th>
            <th>Course Category</th>
            <th style="width: 150px">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($courses as $course)
            <tr>
                <td>{{ $course->id }}</td>
                <td>{{ $course->course_name }}</td>
                <td>{{ $course->course_code }}</td>
                <td>{{ $course->course_category }}</td>

                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning btn-sm editBtn"
                            data-id="{{ $course->id }}"
                            data-course_name="{{ $course->course_name }}"
                            data-course_code="{{ $course->course_code }}"
                            data-course_category="{{ $course->course_category }}"
                        >
                            ✏️ Edit
                        </button>

                        <!-- <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display: inline;">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this course?')">
                                🗑️ Delete
                            </button>
                        </form> -->

                        <form method="POST" action="{{ route('courses.destroy', $course->id) }}" class="d-inline deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm deleteBtn">
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

{{-- ========================= ADD MODAL ========================= --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Course</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('courses.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Course Name *</label>
                            <input name="course_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Course Code *</label>
                            <input name="course_code" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Course Category *</label>
                            <input name="course_category" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================= EDIT MODAL ========================= --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Course</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm" method="POST">
                @csrf 
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Course Name *</label>
                            <input id="edit_course_name" name="course_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Course Code *</label>
                            <input id="edit_course_code" name="course_code" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Course Category *</label>
                            <input id="edit_course_category" name="course_category" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.editBtn');
    const editModal = document.getElementById('editModal');
    const modal = new bootstrap.Modal(editModal);

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            document.getElementById('edit_course_name').value = this.dataset.course_name || '';
            document.getElementById('edit_course_code').value = this.dataset.course_code || '';
            document.getElementById('edit_course_category').value = this.dataset.course_category || '';

            document.getElementById('editForm').action = `/courses/${id}`;

            modal.show();
        });
    });

    @if ($errors->any())
        @if(old('course_name') || old('course_code'))
            modal.show();
        @endif
    @endif
});

document.addEventListener('DOMContentLoaded', function () {

    const deleteButtons = document.querySelectorAll('.deleteBtn');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('.deleteForm');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
</script>

@endsection