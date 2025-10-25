@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Status</h1>

    <form action="{{ route('statuses.update', $status) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $status->name) }}" class="form-control" required>
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
