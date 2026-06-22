@extends('layouts.app')
@section('title', 'Konsultasi Masuk')

@section('content')
    <h2 class="page-title">Konsultasi dari Petani</h2>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @forelse ($konsultasi as $k)
        <div class="card mb-3 p-3">
            <div class="d-flex justify-content-between">
                <strong>{{ $k->judul }}</strong>
                <span class="badge bg-{{ $k->status === 'answered' ? 'success' : 'warning' }}">{{ ucfirst($k->status) }}</span>
            </div>
            <small class="text-muted">
                {{ $k->petani->name ?? 'Petani' }} &middot;
                {{ $k->kategori_tanaman ?? 'Tanpa kategori' }} &middot;
                {{ $k->created_at->format('d M Y H:i') }}
            </small>
            <p class="mt-2 mb-1">{{ $k->deskripsi }}</p>

            @if ($k->foto)
                <img src="{{ asset('storage/' . $k->foto) }}" style="max-width:180px; border-radius:8px;" class="mb-2">
            @endif

            @if ($k->status === 'answered')
                <div class="alert alert-success mb-0 mt-2">
                    <strong>Jawaban kamu:</strong><br>{{ $k->jawaban }}
                </div>
                <a href="{{ route('konsultasi.chat', $k->id) }}" class="btn btn-sm btn-success mt-2">
                    <i class="bi bi-chat-dots"></i> Lanjut Chat
                </a>
            @else
                <form action="{{ route('ahli.konsultasi.jawab', $k->id) }}" method="POST" class="mt-2">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Jawaban / Saran <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="jawaban" rows="3" required placeholder="Minimal 15 karakter..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">Kirim Jawaban</button>
                </form>
            @endif
        </div>
    @empty
        <p class="text-muted">Belum ada konsultasi masuk.</p>
    @endforelse
@endsection
