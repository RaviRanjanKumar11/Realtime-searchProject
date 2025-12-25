<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Realtime Search</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #ffffff);
            min-height: 100vh;
        }
        .login-card {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container" style="max-width: 420px;">
    <div class="card login-card p-4">

        <!-- Header -->
        <div class="text-center mb-4">
            <h3 class="fw-bold">Welcome Back</h3>
            <p class="text-muted mb-0">Login to access realtime search</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <button class="btn btn-primary w-100 mb-3">
                Login
            </button>

            <hr>

            <!-- Google Login -->
            <a href="{{ route('google.login') }}" class="btn btn-danger w-100">
                Login with Google
            </a>
            <div class="text-center">
    <span class="text-muted">Don’t have an account?</span>
    <a href="{{ route('register') }}" class="fw-semibold">
        Register
    </a>
</div>
        </form>

    </div>
</div>

</body>
</html>
