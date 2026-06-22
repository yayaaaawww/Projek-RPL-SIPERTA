@extends('layouts.app')
@section('title', 'Pantau Konsultasi')

@section('content')
    <h2 class="page-title">Pantau Konsultasi</h2>
    <p class="text-muted">Daftar konsultasi antara petani & ahli. Klik untuk membaca isi chat.</p>

    <div class="card activity-card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr><th>Tanggal</th><th>Judul</th><th>Petani</th><th>Ahli</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($konsultasi as $k)
                        <tr>
                            <td>{{ $k->created_at->format('d M Y') }}</td>
                            <td>{{ $k->judul }}</td>
                            <td>{{ $k->petani->name ?? '-' }}</td>
                            <td>{{ $k->ahli->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $k->status === 'answered' ? 'success' : 'warning' }}">
                                    {{ ucfirst($k->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.konsultasi.chat', $k->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-chat-text"></i> Lihat Chat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Belum ada konsultasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
