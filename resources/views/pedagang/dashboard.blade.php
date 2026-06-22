@extends('layouts.app')
@section('title', 'Dashboard Pedagang')

@section('content')
    <h2 class="page-title">Dashboard</h2>

    <div class="stat-row">
        <div class="stat-card"><h6>Total Pesanan</h6><h3>{{ $total_pesanan }}</h3></div>
        <div class="stat-card"><h6>Menunggu Konfirmasi</h6><h3>{{ $menunggu }}</h3></div>
        <div class="stat-card"><h6>Diproses</h6><h3>{{ $diproses }}</h3></div>
        <div class="stat-card"><h6>Selesai</h6><h3>{{ $selesai }}</h3></div>
    </div>

    <hr>

    <div class="card activity-card">
        <div class="card-header">Aktivitas Terbaru</div>
        <div class="card-body">
            <table class="table">
                <thead><tr><th>Waktu</th><th>Produk</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($aktivitas_terbaru as $pesanan)
                        <tr>
                            <td>{{ $pesanan->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $pesanan->panen->nama_komoditas ?? '-' }}</td>
                            <td><span class="badge bg-{{ $pesanan->status === 'completed' ? 'success' : ($pesanan->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($pesanan->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
