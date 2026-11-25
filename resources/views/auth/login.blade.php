<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | Simkos G4</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">
    <main class="card login-card">
        <div class="card-body p-4 p-md-5">
            <p class="text-primary fw-bold mb-1">SIMKOS G4</p>
            <h1 class="h3 mb-2">Masuk ke akun</h1>
            <p class="text-secondary mb-4">Kelola kamar, penghuni, masa sewa, dan pembayaran kos.</p>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">Email atau kata sandi tidak sesuai. Periksa kembali data yang dimasukkan.</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Kata sandi</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="current-password" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-primary w-100" type="submit">Masuk</button>
            </form>
            <p class="text-secondary small mt-4 mb-0">Akun awal tersedia setelah seeder dijalankan.</p>
        </div>
    </main>
</body>
</html>
