@extends('layouts.app')
@section('title', 'Laporan Masuk')

@section('content')
    <h2 class="page-title">Laporan Masuk</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @forelse ($laporan as $l)
        <div class="card mb-3 p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>{{ $l->pelapor->name ?? '-' }}</strong>
                    <span class="text-muted">melaporkan</span>
                    <strong>{{ $l->terlapor->name ?? '-' }}</strong>
                    <span class="badge bg-light text-dark border">{{ ucfirst($l->terlapor->role ?? '-') }}</span>
                    <div class="text-muted" style="font-size:12px;">{{ $l->created_at->format('d M Y, H:i') }}</div>
                    <p class="mb-1 mt-2">{{ $l->alasan }}</p>
                    @if ($l->bukti)
                        <a href="{{ asset('storage/' . $l->bukti) }}" target="_blank">Lihat bukti</a>
                    @endif
                </div>
                <span class="badge bg-{{ $l->status === 'resolved' ? 'success' : 'warning' }} flex-shrink-0">
                    {{ $l->status === 'resolved' ? 'Selesai' : 'Menunggu' }}
                </span>
            </div>

            @if ($l->status !== 'resolved')
                <div class="d-flex gap-2 mt-3">
                    <form action="{{ route('admin.laporan.resolve', $l->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button class="btn btn-sm btn-success">Tandai Selesai</button>
                    </form>
                    <form action="{{ route('admin.laporan.blokir', $l->id) }}" method="POST"
                          onsubmit="return confirm('Blokir akun {{ $l->terlapor->name ?? '' }} & selesaikan laporan?')">
                        @csrf @method('PUT')
                        <button class="btn btn-sm btn-danger">Blokir Terlapor</button>
                    </form>
                </div>
            @elseif ($l->admin)
                <small class="text-muted mt-2">Diselesaikan oleh {{ $l->admin->name }}</small>
            @endif
        </div>
    @empty
        <p class="text-muted">Belum ada laporan masuk.</p>
    @endforelse
@endsection
