<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Forgot password?') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* ===== Background Image ===== */
        body {
            background: url("{{ asset('assets/images/backgrounds/background.jpeg') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }

        /* ===== Dark Overlay ===== */
        .login-wrapper {
            position: relative;
        }

        .login-wrapper::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 1;
        }

        .login-wrapper > * {
            position: relative;
            z-index: 2;
        }

        /* ===== Login Card ===== */
        .card {
            border-radius: 18px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            background: rgba(255, 255, 255, 0.95);
        }

        /* ===== Logo ===== */
        .login-logo img {
            max-width: 120px;
        }

        /* ===== Animation ===== */
        .card {
            animation: fadeUp 0.8s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- Body Wrapper -->
    <div class="page-wrapper login-wrapper min-vh-100 d-flex align-items-center justify-content-center"
         id="main-wrapper">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xxl-3">

                    <div class="card mb-0">
                        <div class="card-body">

                            <!-- Logo -->
                            <div class="text-center mb-4 login-logo">
                                <img src="{{ logo() }}" alt="Logo" class="mb-2">
                                <h3 class="fw-bold mb-0">{{ setting('system_name') }}</h3>
                            </div>

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

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
