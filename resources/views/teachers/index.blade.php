@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Teacher Management</h3>
</div>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">

        <form action="{{ route('teachers.search') }}" method="GET" class="d-flex w-50">
            <input type="text" name="query" value="{{ request('query') }}"
                   class="form-control me-2" placeholder="Search by ID, Name or Email">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Teacher
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
            <th>Teacher ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phones</th>
            <th>Subjects</th>
            <th>Grades</th>
            <th style="width: 150px">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($teachers as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->name }}</td>
                <td>{{ $t->email }}</td>
                <td>{{ $t->phone1 }} / {{ $t->phone2 }}</td>
                <td>{{ $t->subjects }}</td>
                <td>{{ $t->grades }}</td>

                <td>
                    <div class="d-flex gap-2">

                        <button class="btn btn-warning btn-sm editBtn"
                            data-id="{{ $t->id }}"
                            data-name="{{ $t->name }}"
                            data-address="{{ $t->address }}"
                            data-nic="{{ $t->nic }}"
                            data-email="{{ $t->email }}"
                            data-phone1="{{ $t->phone1 }}"
                            data-phone2="{{ $t->phone2 }}"
                            data-subjects="{{ $t->subjects }}"
                            data-grades="{{ $t->grades }}"
                            data-username="{{ $t->username }}"
                        >
                            ✏️ Edit
                        </button>

                        <form action="{{ route('teachers.destroy', $t->id) }}" method="POST" style="display: inline;">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete teacher?')">
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
    <div class="modal-dialog modal-lg"><div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title fw-bold">Add Teacher</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form action="{{ route('teachers.store') }}" method="POST">
            @csrf

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name *</label>
                        <input name="name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input name="email" type="email" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Address *</label>
                        <input name="address" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIC *</label>
                        <input name="nic" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone 1 *</label>
                        <input name="phone1" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone 2</label>
                        <input name="phone2" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Subjects *</label>
                        <input name="subjects" class="form-control" placeholder="Maths, Science" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Grades *</label>
                        <input name="grades" class="form-control" placeholder="10, 11" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Username *</label>
                        <input name="username" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password *</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary px-4">Save</button>
            </div>

        </form>

    </div></div>
</div>

{{-- ========================= EDIT MODAL ========================= --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title fw-bold">Edit Teacher</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="editForm" method="POST">
            @csrf 
            @method('PUT')

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Name *</label>
                        <input id="edit_name" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input id="edit_email" name="email" type="email" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Address *</label>
                        <input id="edit_address" name="address" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIC *</label>
                        <input id="edit_nic" name="nic" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone 1 *</label>
                        <input id="edit_phone1" name="phone1" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone 2</label>
                        <input id="edit_phone2" name="phone2" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Subjects *</label>
                        <input id="edit_subjects" name="subjects" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Grades *</label>
                        <input id="edit_grades" name="grades" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Username *</label>
                        <input id="edit_username" name="username" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password (leave blank to keep current)</label>
                        <input id="edit_password" name="password" type="password" class="form-control">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary px-4">Update</button>
            </div>

        </form>

    </div></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const editButtons = document.querySelectorAll('.editBtn');
    const editModal = document.getElementById('editModal');
    const modal = new bootstrap.Modal(editModal);

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            // Populate form fields
            document.getElementById('edit_name').value = this.dataset.name || '';
            document.getElementById('edit_address').value = this.dataset.address || '';
            document.getElementById('edit_nic').value = this.dataset.nic || '';
            document.getElementById('edit_email').value = this.dataset.email || '';
            document.getElementById('edit_phone1').value = this.dataset.phone1 || '';
            document.getElementById('edit_phone2').value = this.dataset.phone2 || '';
            document.getElementById('edit_subjects').value = this.dataset.subjects || '';
            document.getElementById('edit_grades').value = this.dataset.grades || '';
            document.getElementById('edit_username').value = this.dataset.username || '';
            
            // Clear password field
            document.getElementById('edit_password').value = '';

            // Set form action
            document.getElementById('editForm').action = `/teachers/${id}`;

            console.log('Form action set to:', document.getElementById('editForm').action);

            modal.show();
        });
    });

    // Reopen modal if there are validation errors
    @if ($errors->any())
        // Check if we have old input, which indicates a failed form submission
        @if(old('name'))
            modal.show();
        @endif
    @endif

});
</script>

@endsection