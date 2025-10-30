@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Edit Department') }}</h1>

    <form action="{{ route('departments.update', $department) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" value="{{ old('name', $department->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>{{ __('Code') }}</label>
            <input type="text" name="code" value="{{ old('code', $department->code) }}" class="form-control">
        </div>

        <button class="btn btn-primary">{{ __('Save') }}</button>
    </form>
</div>
@endsection
