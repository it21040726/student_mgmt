@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-4">Student Registration Form</h3>


<form action="{{ route('students.store') }}" method="POST">
    @csrf
    @include('students.form')

    <div class="d-flex justify-content-between">
            <button class="btn btn-secondary" type="reset">Reset</button>
            <button class="btn btn-primary" type="submit">Register</button>
          </div>
</form>
@endsection
