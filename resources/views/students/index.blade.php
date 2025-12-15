@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Student Management</h3>
</div>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">

        <form action="{{ route('students.search') }}" method="GET" class="d-flex w-50">
            <input type="text" name="query" value="{{ request('query') }}"
                   class="form-control me-2" placeholder="Search by ID, Name, Email or Classroom">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <div class="d-flex gap-2">
            <a href="{{ route('students.export.pdf') }}" class="btn btn-danger text-white">
                📄 Export PDF
            </a>
            <button class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#importModal">
                📥 Import CSV
            </button>
            <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addModal">
                + Add Student
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('import_errors'))
    <div class="alert alert-warning">
        <strong>Import Errors:</strong>
        <ul class="mb-0">
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
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
            <th>Student ID</th>
            <th>Profile</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Guardian Phone</th>
            <th>Grade</th>
            <th>Classroom</th>
            <th style="width: 150px">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($students as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>
                    @if($s->profile_img && $s->profile_img !== 'default.jpg')
                        <img src="{{ asset('storage/'.$s->profile_img) }}" 
                             alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #ddd; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 18px; color: #666;">👤</span>
                        </div>
                    @endif
                </td>
                <td>{{ $s->name }}</td>
                <td>{{ $s->email }}</td>
                <td>{{ $s->phone1 }} @if($s->phone2) / {{ $s->phone2 }} @endif</td>
                <td>{{ $s->guardian_phone1 }}</td>
                <td>{{ $s->current_grade }}</td>
                <td>{{ $s->classroom }}</td>

                <td>
                    <div class="d-flex gap-2">

                        <button class="btn btn-warning btn-sm editBtn"
                            data-id="{{ $s->id }}"
                            data-name="{{ $s->name }}"
                            data-address="{{ $s->address }}"
                            data-email="{{ $s->email }}"
                            data-phone1="{{ $s->phone1 }}"
                            data-phone2="{{ $s->phone2 }}"
                            data-guardian_phone1="{{ $s->guardian_phone1 }}"
                            data-current_grade="{{ $s->current_grade }}"
                            data-classroom="{{ $s->classroom }}"
                            data-id_front="{{ $s->id_front }}"
                            data-id_back="{{ $s->id_back }}"
                            data-profile_img="{{ $s->profile_img }}"
                        >
                            ✏️ Edit
                        </button>

                        <form method="POST" action="{{ route('students.destroy', $s->id) }}" class="d-inline deleteForm">
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

{{-- ========================= IMPORT CSV MODAL ========================= --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import Students from CSV</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select CSV File *</label>
                        <input type="file" name="csv_file" accept=".csv" class="form-control" required>
                        <small class="text-muted">Maximum file size: 2MB</small>
                    </div>

                    <div class="alert alert-info">
                        <strong>CSV Format:</strong>
                        <p class="mb-2">Your CSV file should have the following columns in order:</p>
                        <ol class="mb-2">
                            <li>Name</li>
                            <li>Address</li>
                            <li>Email</li>
                            <li>Phone 1</li>
                            <li>Phone 2 (optional)</li>
                            <li>Guardian Phone</li>
                            <li>Current Grade</li>
                            <li>Classroom</li>
                        </ol>
                        <small><strong>Note:</strong> Profile images and ID cards will be set to default values. You can update them individually later.</small>
                    </div>

                    <div class="mb-3">
                        <a href="#" onclick="downloadSampleCSV(); return false;" class="btn btn-sm btn-outline-primary">
                            📄 Download Sample CSV
                        </a>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================= ADD MODAL ========================= --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title fw-bold">Add Student</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label class="form-label">Phone 1 *</label>
                        <input name="phone1" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone 2</label>
                        <input name="phone2" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Guardian Phone *</label>
                        <input name="guardian_phone1" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Current Grade *</label>
                        <select name="current_grade" class="form-control" required>
                            <option value="">Select Grade</option>
                            @foreach($grades as $g)
                            <option value="{{ $g->grade_name }}">{{ $g->grade_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Classroom *</label>
                        <select name="classroom" class="form-control" required>
                            <option value="">Select Classroom</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->class_name }}">{{ $c->class_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_img" accept="image/*" 
                            onchange="previewImage(this, 'create_profile_preview')" class="form-control">
                        <img id="create_profile_preview" style="display:none;width:100px;height:100px;object-fit:cover;margin-top:10px;border-radius:50%;" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Identity Card Front</label>
                        <input type="file" name="id_front" accept="image/*" 
                            onchange="previewImage(this, 'create_front_preview')" class="form-control">
                        <img id="create_front_preview" style="display:none;width:150px;margin-top:10px;" />    
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Identity Card Back</label>
                        <input type="file" name="id_back" accept="image/*" 
                            onchange="previewImage(this, 'create_back_preview')" class="form-control">
                        <img id="create_back_preview" style="display:none;width:150px;margin-top:10px;" />
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
            <h5 class="modal-title fw-bold">Edit Student</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data">
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
                        <label class="form-label">Phone 1 *</label>
                        <input id="edit_phone1" name="phone1" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone 2</label>
                        <input id="edit_phone2" name="phone2" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Guardian Phone *</label>
                        <input id="edit_guardian_phone1" name="guardian_phone1" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Current Grade *</label>
                        <select id="edit_current_grade" name="current_grade" class="form-control" required>
                            <option value="">Select Grade</option>
                            @foreach($grades as $g)
                            <option value="{{ $g->grade_name }}">{{ $g->grade_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Classroom *</label>
                        <select id="edit_classroom" name="classroom" class="form-control" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->class_name }}">{{ $c->class_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_img" accept="image/*"
                            onchange="previewImage(this, 'edit_profile_preview')" class="form-control">
                        <img id="edit_profile_preview" style="width:100px;height:100px;object-fit:cover;margin-top:10px;border-radius:50%;display:none;" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Identity Card Front</label>
                        <input type="file" name="id_front" accept="image/*"
                            onchange="previewImage(this, 'edit_front_preview')" class="form-control">
                        <img id="edit_front_preview" style="width:150px;margin-top:10px;display:none;" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Identity Card Back</label>
                        <input type="file" name="id_back" accept="image/*"
                            onchange="previewImage(this, 'edit_back_preview')" class="form-control">
                        <img id="edit_back_preview" style="width:150px;margin-top:10px;display:none;" />
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

            document.getElementById('edit_name').value = this.dataset.name || '';
            document.getElementById('edit_address').value = this.dataset.address || '';
            document.getElementById('edit_email').value = this.dataset.email || '';
            document.getElementById('edit_phone1').value = this.dataset.phone1 || '';
            document.getElementById('edit_phone2').value = this.dataset.phone2 || '';
            document.getElementById('edit_guardian_phone1').value = this.dataset.guardian_phone1 || '';
            document.getElementById('edit_current_grade').value = this.dataset.current_grade || '';
            document.getElementById('edit_classroom').value = this.dataset.classroom || '';

            const profileImg = this.dataset.profile_img;
            const idFront = this.dataset.id_front;
            const idBack = this.dataset.id_back;

            const profilePreview = document.getElementById('edit_profile_preview');
            const frontPreview = document.getElementById('edit_front_preview');
            const backPreview = document.getElementById('edit_back_preview');

            if (profileImg && profileImg !== 'default.jpg') {
                profilePreview.src = `/storage/${profileImg}`;
                profilePreview.style.display = 'block';
            } else {
                profilePreview.style.display = 'none';
            }

            if (idFront && idFront !== 'default.jpg') {
                frontPreview.src = `/storage/${idFront}`;
                frontPreview.style.display = 'block';
            } else {
                frontPreview.style.display = 'none';
            }

            if (idBack && idBack !== 'default.jpg') {
                backPreview.src = `/storage/${idBack}`;
                backPreview.style.display = 'block';
            } else {
                backPreview.style.display = 'none';
            }

            document.getElementById('editForm').action = `/students/${id}`;

            modal.show();
        });
    });

});

function previewImage(input, imgPreviewId) {
    let file = input.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById(imgPreviewId).src = e.target.result;
            document.getElementById(imgPreviewId).style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function downloadSampleCSV() {
    const csvContent = "Name,Address,Email,Phone 1,Phone 2,Guardian Phone,Current Grade,Classroom\n" +
                      "John Doe,123 Main St,john@example.com,1234567890,0987654321,1122334455,Grade 10,Class A\n" +
                      "Jane Smith,456 Oak Ave,jane@example.com,2345678901,,2233445566,Grade 9,Class B";
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'student_import_sample.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

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