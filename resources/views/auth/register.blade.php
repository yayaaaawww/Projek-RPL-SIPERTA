<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPERTA - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('asset/style.css') }}">
</head>
<body>
    <div class="container" style="max-width:520px;">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-12">
                <div class="card p-4 shadow-sm my-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('asset/logo.png') }}" alt="Logo SIPERTA"
                             style="height:70px; margin-bottom:10px;" onerror="this.style.display='none'">
                        <h2 class="fw-bold" style="color:#2F6F4E;">Daftar SIPERTA</h2>
                        <p class="text-muted">Buat akun baru</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Daftar sebagai</label>
                            <select class="form-select" name="role" required>
                                <option value="">-- Pilih peran --</option>
                                <option value="petani"   @selected(old('role')=='petani')>Petani</option>
                                <option value="ahli"     @selected(old('role')=='ahli')>Ahli</option>
                                <option value="pedagang" @selected(old('role')=='pedagang')>Pedagang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. HP <small class="text-muted">(opsional)</small></label>
                            <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat <small class="text-muted">(opsional)</small></label>
                            <input type="text" class="form-control" name="alamat" value="{{ old('alamat') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ulangi Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <p class="text-center mb-3">Sudah punya akun?
                            <a href="{{ route('login') }}">Login di sini</a>
                        </p>
                        <button type="submit" class="btn btn-success w-100">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
