@extends('layouts.app')
@section('title', 'Konsultasi')

@section('content')
    <h2 class="page-title">Konsultasi Kendala</h2>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        {{-- Form kirim keluhan --}}
        <div class="col-md-5 mb-4">
            <div class="card p-3">
                <h5 class="mb-3">Laporkan Kendala</h5>
                <form action="{{ route('petani.konsultasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Judul Keluhan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul" value="{{ old('judul') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Tanaman</label>
                        <input type="text" class="form-control" name="kategori_tanaman" value="{{ old('kategori_tanaman') }}" placeholder="mis. Padi, Cabai">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Gejala <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                        <small class="text-muted">Minimal 10 karakter.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Kondisi Tanaman <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="foto" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Kirim Konsultasi</button>
                </form>
            </div>
        </div>

        {{-- Riwayat konsultasi --}}
        <div class="col-md-7">
            <h5 class="mb-3">Riwayat Konsultasi</h5>
            @forelse ($konsultasi as $k)
                <div class="card mb-3 p-3">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $k->judul }}</strong>
                        <span class="badge bg-{{ $k->status === 'answered' ? 'success' : 'warning' }}">{{ ucfirst($k->status) }}</span>
                    </div>
                    <small class="text-muted">{{ $k->kategori_tanaman ?? 'Tanpa kategori' }} &middot; {{ $k->created_at->format('d M Y') }}</small>
                    <form action="{{ route('petani.konsultasi.destroy', $k->id) }}" method="POST" class="mt-1"
                          onsubmit="return confirm('Hapus konsultasi ini beserta chat-nya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                    <p class="mt-2 mb-1">{{ $k->deskripsi }}</p>
                    @if ($k->foto)
                        <img src="{{ asset('storage/' . $k->foto) }}" style="max-width:150px; border-radius:8px;" class="mb-2">
                    @endif
                    @if ($k->jawaban)
                        <div class="alert alert-success mb-0 mt-2">
                            <strong>Jawaban {{ $k->ahli->name ?? 'Ahli' }}:</strong><br>
                            {{ $k->jawaban }}
                        </div>
                        <a href="{{ route('konsultasi.chat', $k->id) }}" class="btn btn-sm btn-success mt-2">
                            <i class="bi bi-chat-dots"></i> Lanjut Chat
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-muted">Belum ada konsultasi.</p>
            @endforelse
        </div>
    </div>
@endsection
