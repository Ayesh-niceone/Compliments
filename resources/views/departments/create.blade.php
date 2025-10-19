@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Department</h1>

    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Position</label>
            <input type="text" name="position" class="form-control">
        </div>

        <button class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
