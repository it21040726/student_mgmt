@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Grade Management</h3>
</div>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <form action="{{ route('grades.search') }}" method="GET" class="d-flex w-50">
            <input type="text" name="query" value="{{ request('query') }}"
                   class="form-control me-2" placeholder="Search by Grade Name">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Grade
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
            <th>Grade ID</th>
            <th>Grade Name</th>
            <th style="width: 150px">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($grades as $grade)
            <tr>
                <td>{{ $grade->id }}</td>
                <td>{{ $grade->grade_name }}</td>

                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning btn-sm editBtn"
                            data-id="{{ $grade->id }}"
                            data-grade_name="{{ $grade->grade_name }}"
                        >
                            ✏️ Edit
                        </button>

                        <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display: inline;">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this grade?')">
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Grade</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('grades.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Grade ID *</label>
                        <input id="edit_grade_ID" name="grade_id" class="form-control" placeholder="1,2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grade Name *</label>
                        <input name="grade_name" class="form-control" placeholder="e.g., Grade 10" required>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Grade</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm" method="POST">
                @csrf 
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Grade Name *</label>
                        <input id="edit_grade_name" name="grade_name" class="form-control" required>
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

            document.getElementById('edit_grade_name').value = this.dataset.grade_name || '';

            document.getElementById('editForm').action = `/grades/${id}`;

            modal.show();
        });
    });

    @if ($errors->any())
        @if(old('grade_name'))
            modal.show();
        @endif
    @endif
});
</script>

@endsection