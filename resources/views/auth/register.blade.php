@extends('layouts.auth')

@section('title', __('Register'))

@section('content')
    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">{{ __('Name') }}</label>
            <input
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('Email') }}</label>
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                required
                autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('Password') }}</label>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">{{ __('Confirm Password') }}</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                required
                autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit"
                class="btn btn-primary w-100 py-8 fs-4 rounded-2 mb-3">
            {{ __('Register') }}
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-primary text-decoration-none small">
                {{ __('Already registered?') }}
            </a>
        </div>
    </form>
@endsection
