@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Completion Type</h1>

    <form action="{{ route('completion_types.update', $completion_type) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $completion_type->name) }}" class="form-control" required>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
