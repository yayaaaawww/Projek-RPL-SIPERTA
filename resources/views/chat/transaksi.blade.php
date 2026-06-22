@extends('layouts.app')
@section('title', 'Chat Transaksi')

@section('content')
    @php
        $isPetani = auth()->id() === $pesanan->petani_id;
        $lawan = $isPetani ? ($pesanan->pedagang->name ?? 'Pedagang') : ($pesanan->petani->name ?? 'Petani');
        $kembali = $isPetani ? route('petani.pesanan') : route('pedagang.pesanan');
    @endphp

    <a href="{{ $kembali }}" class="text-decoration-none">&larr; Kembali ke Pesanan</a>
    <h2 class="page-title mt-2">Chat: {{ $pesanan->panen->nama_komoditas ?? 'Transaksi' }}</h2>

    <div class="chat-box">
        <div class="chat-header">
            <i class="bi bi-person-circle"></i>
            <div>
                <div class="chat-title">{{ $lawan }}</div>
                <small class="text-muted">
                    {{ $pesanan->jumlah_beli }} kg &middot;
                    Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }} &middot;
                    {{ ucfirst($pesanan->status) }}
                </small>
            </div>
        </div>

        <div class="chat-body">
            @forelse ($chat as $c)
                <div class="message {{ $c->pengirim_id === auth()->id() ? 'sent' : 'received' }}">
                    @if ($c->pengirim_id !== auth()->id())
                        <strong>{{ $c->pengirim->name ?? 'User' }}:</strong><br>
                    @endif
                    {{ $c->pesan }}
                </div>
            @empty
                <p class="text-muted text-center">Belum ada pesan. Mulai obrolan untuk koordinasi pengambilan barang. 🌾</p>
            @endforelse
        </div>

        <form action="{{ route('transaksi.chat.send', $pesanan->id) }}" method="POST" class="chat-input">
            @csrf
            <input type="text" name="pesan" class="form-control" placeholder="Ketik pesan..." required autocomplete="off">
            <button type="submit" class="btn btn-success"><i class="bi bi-send"></i></button>
        </form>
    </div>
@endsection
