@extends('layouts.auth')

@section('title', __('Confirm Password'))

@section('content')
    <p class="text-muted small mb-4">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" role="alert">
            {{ $errors->first('password') }}
        </div>
    @endif

    <!-- Confirm Password Form -->
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label">{{ __('Password') }}</label>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autofocus
                autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit"
                class="btn btn-primary w-100 py-8 fs-4 rounded-2 mb-3">
            {{ __('Confirm') }}
        </button>

        <div class="text-center">
            <a href="{{ route('dashboard') }}" class="text-primary text-decoration-none small">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
@endsection
