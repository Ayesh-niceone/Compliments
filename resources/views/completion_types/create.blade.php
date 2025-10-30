@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Create Completion Type') }}</h1>

    <form action="{{ route('completion_types.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button class="btn btn-primary">{{ __('Create') }}</button>
    </form>
</div>
@endsection
