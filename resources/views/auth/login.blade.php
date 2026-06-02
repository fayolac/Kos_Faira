<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Rumah Kos Faira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="auth-title">Login - Rumah Kos Faira</div>
        </div>

        @if(session('error'))
            <div class="alert-error-custom">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert-success-custom">{{ session('success') }}</div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Email
                </label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       placeholder="Masukkan email" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Password 
                </label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Masukkan password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-orange-full">Login</button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="/register">Daftar</a>
        </div>
    </div>

</body>
</html>