@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('compliments.index') }}" class="btn btn-link">&larr; {{ __('Back') }}</a>

    <h1>{{ __('Compliment') }} #{{ $compliment->id }}</h1>

    {{-- ========== COMPLIMENT DETAILS ========== --}}
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>{{ __('Customer Name') }}:</strong> {{ $compliment->customer_name ?? '-' }}</p>
            <p><strong>{{ __('Phone') }}:</strong> {{ $compliment->phone ?? '-' }}</p>
            <p><strong>{{ __('Plate Number') }}:</strong> {{ $compliment->plate_number ?? '-' }}</p>
            <p><strong>{{ __('Created At') }}:</strong> {{ $compliment->created_at?->format('Y-m-d H:i') ?? '-' }}</p>
            <p><strong>{{ __('Closed At') }}:</strong> {{ $compliment->closed_at?->format('Y-m-d H:i') ?? '-' }}</p>
            <p><strong>{{ __('Department') }}:</strong> {{ $compliment->department->name ?? '-' }}</p>
            <p><strong>{{ __('Care User') }}:</strong> {{ $compliment->careUser->name ?? '-' }}</p>
            <p><strong>{{ __('Status') }}:</strong> {{ $compliment->status->name ?? '-' }}</p>
            <p><strong>{{ __('Completion Type') }}:</strong> {{ $compliment->completion_type->name ?? '-' }}</p>
            <p><strong>{{ __('Target Type') }}:</strong> {{ $compliment->target_type ?? '-' }}</p>
        </div>
    </div>

    {{-- ========== CUSTOMER COMMENT ========== --}}
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ __('Customer Comment') }}</h5>
            <p>{{ $compliment->comment ?? '-' }}</p>
        </div>
    </div>

    {{-- ========== EXISTING CARE COMMENT ========== --}}
    @if($compliment->care_comment)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ __('Care User Comment') }}</h5>
            <p>{{ $compliment->care_comment }}</p>
        </div>
    </div>
    @endif

    {{-- ========== UPDATE FORM (Care Comment + Status) ========== --}}
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ __('Update Care Comment & Status') }}</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('compliments.update', $compliment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="care_comment" class="form-label">{{ __('Care Comment') }}</label>
                    <textarea name="care_comment" id="care_comment" class="form-control" rows="3" required>{{ old('care_comment', $compliment->care_comment) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="status_id" class="form-label">{{ __('Status') }}</label>
                    <select name="status_id" id="status_id" class="form-select" required>
                        <option value="">-- {{ __('Select Status') }} --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ $compliment->status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
