@extends('layouts.app')
@section('title', 'Profil')

@section('content')
    <h2 class="page-title">Profil Saya</h2>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card p-4" style="max-width:600px;">
        <div class="text-center mb-3">
            <i class="bi bi-person-circle" style="font-size:90px; color:#2F6F4E;"></i>
            <h5 class="mt-2">{{ $user->name }}</h5>
            <span class="badge bg-success">{{ ucfirst($user->role) }}</span>
        </div>

        <form action="{{ route('petani.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">No. HP</label>
                <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" class="form-control" name="alamat" value="{{ old('alamat', $user->alamat) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">No. Rekening</label>
                <input type="text" class="form-control" name="no_rekening" value="{{ old('no_rekening', $user->no_rekening) }}">
            </div>
            <hr>
            <p class="text-muted" style="font-size:13px;">Isi password hanya jika ingin mengganti:</p>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ulangi Password</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </form>
    </div>
@endsection
