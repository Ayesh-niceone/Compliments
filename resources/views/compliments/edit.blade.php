@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Edit Compliment') }}</h1>

        <form action="{{ route('compliments.update', $compliment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="care_comment" class="form-label">{{ __('Care Comment') }}</label>
                <textarea name="care_comment" id="care_comment" class="form-control" rows="4" required>{{ old('care_comment', $compliment->care_comment) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="status_id" class="form-label">{{ __('Status') }}</label>
                <select name="status_id" id="status_id" class="form-select" required>
                    <option value="">-- {{ __('Select Status') }} --</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" {{ $compliment->status_id == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
@endsection
