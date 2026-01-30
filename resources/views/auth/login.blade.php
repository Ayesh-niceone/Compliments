@extends('layouts.auth')

@section('title', __('Log in'))

@section('content')
    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label mb-0">Password</label>
                <a href="{{ route('password.request') }}" class="text-primary text-decoration-none small">
                    {{ __('Forgot password?') }}
                </a>
            </div>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input primary"
                       type="checkbox"
                       name="remember"
                       id="remember"
                       checked>
                <label class="form-check-label text-dark" for="remember">
                    Remember this device
                </label>
            </div>
        </div>

        <button type="submit"
                class="btn btn-primary w-100 py-8 fs-4 rounded-2">
            {{ __('Log in') }}
        </button>
    </form>
@endsection
