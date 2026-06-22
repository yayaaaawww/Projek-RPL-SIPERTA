<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPERTA - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('asset/style.css') }}">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-5">
                <div class="card p-4 shadow-sm">
                    <div class="text-center mb-4">
                        <img src="{{ asset('asset/logo.png') }}" alt="Logo SIPERTA"
                             style="height:70px; margin-bottom:10px;" onerror="this.style.display='none'">
                        <h2 class="fw-bold" style="color:#2F6F4E;">SIPERTA</h2>
                        <p class="text-muted">Sistem Informasi Pertanian Terpadu</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                   value="{{ old('email') }}" placeholder="Masukkan email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password"
                                   placeholder="Masukkan password" required>
                        </div>
                        <p class="text-center mb-3">Belum punya akun?
                            <a href="{{ route('register') }}">Daftar di sini</a>
                        </p>
                        <button type="submit" class="btn btn-success w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
