@extends('layouts.auth')

@section('title', __('Forgot password?'))

@section('content')
    <p class="text-muted small mb-4">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4" role="alert">
            {{ $errors->first('email') }}
        </div>
    @endif

    <!-- Forgot Password Form -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label">{{ __('Email') }}</label>
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

        <button type="submit"
                class="btn btn-primary w-100 py-8 fs-4 rounded-2 mb-3">
            {{ __('Email Password Reset Link') }}
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-primary text-decoration-none small">
                {{ __('Back to login') }}
            </a>
        </div>
    </form>
@endsection
