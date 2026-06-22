@extends('layouts.app')
@section('title', 'Kelola Pengguna')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="page-title mb-0">Kelola Pengguna</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahUser">
            <i class="bi bi-plus-lg"></i> Tambah Akun
        </button>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card activity-card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($u->role) }}</span></td>
                            <td>
                                <span class="badge bg-{{ $u->status === 'suspended' ? 'danger' : 'success' }}">
                                    {{ $u->status === 'suspended' ? 'Diblokir' : 'Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUser{{ $u->id }}">Edit</button>

                                    @if ($u->status === 'suspended')
                                        <form action="{{ route('admin.users.unblokir', $u->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button class="btn btn-sm btn-success">Aktifkan</button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#blokir{{ $u->id }}">Blokir</button>
                                    @endif

                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus akun {{ $u->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editUser{{ $u->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Akun</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.users.update', $u->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="name" value="{{ $u->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="{{ $u->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Role</label>
                                                <select class="form-select" name="role" required>
                                                    <option value="petani"   @selected($u->role=='petani')>Petani</option>
                                                    <option value="ahli"     @selected($u->role=='ahli')>Ahli</option>
                                                    <option value="pedagang" @selected($u->role=='pedagang')>Pedagang</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Blokir --}}
                        <div class="modal fade" id="blokir{{ $u->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Blokir {{ $u->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.users.blokir', $u->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <label class="form-label">Alasan Pemblokiran <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="alasan" rows="3" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Blokir Akun</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Akun --}}
    <div class="modal fade" id="tambahUser" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Akun Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="">-- Pilih role --</option>
                                <option value="petani">Petani</option>
                                <option value="ahli">Ahli</option>
                                <option value="pedagang">Pedagang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" minlength="8" required>
                            <small class="text-muted">Minimal 8 karakter.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Buat Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
