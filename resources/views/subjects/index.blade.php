@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Subject Management</h3>
</div>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <form action="{{ route('subjects.search') }}" method="GET" class="d-flex w-50">
            <input type="text" name="query" value="{{ request('query') }}"
                   class="form-control me-2" placeholder="Search by Subject Name">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Subject
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
            <th>Subject ID</th>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th style="width: 150px">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($subjects as $subject)
            <tr>
                <td>{{ $subject->id }}</td>
                <td>{{ $subject->subject_code }}</td>
                <td>{{ $subject->subject_name }}</td>

                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning btn-sm editBtn"
                            data-id="{{ $subject->id }}"
                            data-subject_name="{{ $subject->subject_name }}"
                            data-subject_code="{{ $subject->subject_code }}"
                        >
                            ✏️ Edit
                        </button>

                        <!-- <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display: inline;">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this subject?')">
                                🗑️ Delete
                            </button>
                        </form> -->

                        <form method="POST" action="{{ route('subjects.destroy', $subject->id) }}" class="d-inline deleteForm">
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Subject</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Subject Code *</label>
                        <input name="subject_code" class="form-control" placeholder="e.g., M001" required>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Subject Name *</label>
                        <input name="subject_name" class="form-control" placeholder="e.g., Maths" required>
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
                <h5 class="modal-title fw-bold">Edit Subject</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm" method="POST">
                @csrf 
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Subject Code *</label>
                        <input id="edit_subject_code" name="subject_code" class="form-control" placeholder="e.g., M001" required>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Subject Name *</label>
                        <input id="edit_subject_name" name="subject_name" class="form-control" placeholder="e.g., Maths" required>
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

            document.getElementById('edit_subject_code').value = this.dataset.subject_code || '';
            document.getElementById('edit_subject_name').value = this.dataset.subject_name || '';

            document.getElementById('editForm').action = `/subjects/${id}`;

            modal.show();
        });
    });

    @if ($errors->any())
        @if(old('subject_name') || old('subject_code'))
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