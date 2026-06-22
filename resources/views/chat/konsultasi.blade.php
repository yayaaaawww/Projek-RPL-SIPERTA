@extends('layouts.app')
@section('title', 'Chat Konsultasi')

@section('content')
    @php
        $lawan = auth()->id() === $konsultasi->petani_id
            ? ($konsultasi->ahli->name ?? 'Ahli')
            : ($konsultasi->petani->name ?? 'Petani');
    @endphp

    <a href="{{ auth()->user()->role === 'petani' ? route('petani.konsultasi') : route('ahli.konsultasi') }}" class="text-decoration-none">&larr; Kembali ke Konsultasi</a>
    <h2 class="page-title mt-2">Chat: {{ $konsultasi->judul }}</h2>

    <div class="chat-box">
        <div class="chat-header">
            <i class="bi bi-person-circle"></i>
            <div>
                <div class="chat-title">{{ $lawan }}</div>
                <small class="text-muted">Konsultasi: {{ $konsultasi->judul }}</small>
            </div>
        </div>

        <div class="chat-body">
            {{-- Pesan awal & jawaban ahli sebagai pembuka --}}
            <div class="message received">
                <strong>{{ $konsultasi->petani->name ?? 'Petani' }}:</strong><br>
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
                <div class="message {{ $c->pengirim_id === auth()->id() ? 'sent' : 'received' }}">
                    @if ($c->pengirim_id !== auth()->id())
                        <strong>{{ $c->pengirim->name ?? 'User' }}:</strong><br>
                    @endif
                    {{ $c->pesan }}
                </div>
            @endforeach
        </div>

        <form action="{{ route('konsultasi.chat.send', $konsultasi->id) }}" method="POST" class="chat-input">
            @csrf
            <input type="text" name="pesan" class="form-control" placeholder="Ketik pesan..." required autocomplete="off">
            <button type="submit" class="btn btn-success"><i class="bi bi-send"></i></button>
        </form>
    </div>
@endsection
