@extends('layouts.app')
@section('title', 'Data Pertanian')

@section('content')
    <h2 class="page-title">Data Pertanian</h2>

    <div class="lahan-grid">
        @forelse ($tanaman as $t)
            <div class="lahan-card">
                <p><strong>{{ $t->nama_lahan ?? 'Tanpa Nama Lahan' }}</strong></p>
                <p>Jenis: {{ $t->jenis_tanaman }}</p>
                <p>Alamat: {{ $t->alamat_lahan ?? '-' }}</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('petani.perawatan', $t->id) }}" class="btn btn-sm btn-success">Log Harian</a>
                    <form action="{{ route('petani.data.destroy', $t->id) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada data pertanian. Klik tombol + untuk menambah.</p>
        @endforelse

        <div class="add-card" data-bs-toggle="modal" data-bs-target="#tambahModal">
            <i class="bi bi-plus-lg"></i>
        </div>
    </div>

    {{-- Modal Tambah Data --}}
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Pertanian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('petani.data.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lahan</label>
                            <input type="text" class="form-control" name="nama_lahan" value="{{ old('nama_lahan') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Tanaman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="jenis_tanaman" value="{{ old('jenis_tanaman') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lahan</label>
                            <input type="text" class="form-control" name="alamat_lahan" value="{{ old('alamat_lahan') }}">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
