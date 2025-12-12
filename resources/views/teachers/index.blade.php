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
                <td>
                    @foreach(json_decode($t->subjects) as $s)
                        <p>{{ $s }}</p>
                    @endforeach
                </td>
                <td>
                    @foreach(json_decode($t->grades) as $g)
                        <p>{{ $g }}</p>
                    @endforeach
                </td>

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
                            data-subjects='@json($t->subjects)'
                            data-grades='@json($t->grades)'
                            data-username="{{ $t->username }}"
                            data-id_front="{{ $t->id_front }}"
                            data-id_back="{{ $t->id_back }}"
                        >
                            ✏️ Edit
                        </button>

                        <!-- <form action="{{ route('teachers.destroy', $t->id) }}" method="POST" style="display: inline;">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete teacher?')">
                                🗑️ Delete
                            </button>
                        </form> -->

                        <form method="POST" action="{{ route('teachers.destroy', $t->id) }}" class="d-inline deleteForm">
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
    <div class="modal-dialog modal-lg"><div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title fw-bold">Add Teacher</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
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

                    <div class="col-md-6">
                        <label class="form-label">Address *</label>
                        <input name="address" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIC *</label>
                        <input name="nic" class="form-control" required>
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
                        <select name="subjects[]" class="form-control" multiple required>
                            @foreach($subjects as $s)
                            <option value="{{ $s->subject_name }}">{{ $s->subject_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Grades *</label>
                        <select name="grades[]" class="form-control" multiple required>
                            @foreach($grades as $g)
                            <option value="{{ $g->grade_name }}">{{ $g->grade_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple.</small>
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

                    <div class="col-md-6">
                        <label class="form-label">Address *</label>
                        <input id="edit_address" name="address" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIC *</label>
                        <input id="edit_nic" name="nic" class="form-control" required>
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
                        <select id="edit_subjects" name="subjects[]" class="form-control" multiple required>
                            @foreach($subjects as $s)
                            <option value="{{ $s->subject_name }}">{{ $s->subject_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Grades *</label>
                        <select id="edit_grades" name="grades[]" class="form-control" multiple required>
                            @foreach($grades as $g)
                            <option value="{{ $g->grade_name }}">{{ $g->grade_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple.</small>
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

            document.getElementById('edit_name').value = this.dataset.name || '';
            document.getElementById('edit_address').value = this.dataset.address || '';
            document.getElementById('edit_nic').value = this.dataset.nic || '';
            document.getElementById('edit_email').value = this.dataset.email || '';
            document.getElementById('edit_phone1').value = this.dataset.phone1 || '';
            document.getElementById('edit_phone2').value = this.dataset.phone2 || '';
            document.getElementById('edit_username').value = this.dataset.username || '';
            document.getElementById('edit_password').value = '';

            const subjects = JSON.parse(this.dataset.subjects || "[]");
            const grades   = JSON.parse(this.dataset.grades || "[]");

            const subjectSelect = document.getElementById('edit_subjects');
            const gradeSelect   = document.getElementById('edit_grades');

            [...subjectSelect.options].forEach(opt => {
                opt.selected = subjects.includes(opt.value);
            });

            [...gradeSelect.options].forEach(opt => {
                opt.selected = grades.includes(opt.value);
            });

            const idFrontPreview = document.getElementById('edit_front_preview');
            const idBackPreview = document.getElementById('edit_back_preview');

            const idFront = this.dataset.id_front;
            const idBack = this.dataset.id_back;

            if (idFront) {
                idFrontPreview.src = `/storage/${idFront}`;
                idFrontPreview.style.display = 'block';
            } else {
                idFrontPreview.src = '';
                idFrontPreview.style.display = 'none';
            }

            if (idBack) {
                idBackPreview.src = `/storage/${idBack}`;
                idBackPreview.style.display = 'block';
            } else {
                idBackPreview.src = '';
                idBackPreview.style.display = 'none';
            }

            document.getElementById('editForm').action = `/teachers/${id}`;

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