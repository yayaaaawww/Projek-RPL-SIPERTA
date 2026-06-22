@extends('layouts.app')
@section('title', 'Baca Chat Konsultasi')

@section('content')
    <a href="{{ route('admin.konsultasi') }}" class="text-decoration-none">&larr; Kembali ke Daftar Konsultasi</a>
    <h2 class="page-title mt-2">{{ $konsultasi->judul }}</h2>
    <p class="text-muted">
        Petani: <strong>{{ $konsultasi->petani->name ?? '-' }}</strong> &middot;
        Ahli: <strong>{{ $konsultasi->ahli->name ?? '-' }}</strong>
        <span class="badge bg-secondary ms-1">mode pantau (read-only)</span>
    </p>

    <div class="chat-box">
        <div class="chat-body">
            {{-- Keluhan awal & jawaban --}}
            <div class="message received">
                <strong>{{ $konsultasi->petani->name ?? 'Petani' }} (keluhan):</strong><br>
                {{ $konsultasi->deskripsi }}
            </div>
            @if ($konsultasi->jawaban)
                <div class="message received" style="background:#d1e7dd;">
                    <strong>{{ $konsultasi->ahli->name ?? 'Ahli' }} (jawaban):</strong><br>
                    {{ $konsultasi->jawaban }}
                </div>
            @endif

            {{-- Lanjutan chat --}}
            @foreach ($chat as $c)
                <div class="message received">
                    <strong>{{ $c->pengirim->name ?? 'User' }}:</strong><br>
                    {{ $c->pesan }}
                </div>
            @endforeach

            @if ($chat->isEmpty() && ! $konsultasi->jawaban)
                <p class="text-muted text-center">Belum ada percakapan.</p>
            @endif
        </div>
        <div class="p-3 text-center text-muted" style="border-top:1px solid #eee; font-size:13px;">
            <i class="bi bi-eye"></i> Admin hanya bisa memantau, tidak bisa mengirim pesan.
        </div>
    </div>
@endsection
