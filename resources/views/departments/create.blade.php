@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Create Department') }}</h1>

    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>{{ __('Code') }}</label>
            <input type="text" name="code" class="form-control">
        </div>

        <button class="btn btn-primary">{{ __('Create') }}</button>
    </form>
</div>
@endsection
