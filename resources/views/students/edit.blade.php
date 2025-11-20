@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-4">Edit Student</h3>

<form action="{{ route('students.update', $student->id) }}" method="POST">
    @csrf
    @method('PUT')

    @include('students.form')

    <button class="btn btn-primary px-4">✔️ Update Student</button>
</form>
@endsection
