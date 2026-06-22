@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
    <h2 class="page-title">Dashboard</h2>

    <div class="stat-row">
        <div class="stat-card"><h6>Total Pesanan</h6><h3>{{ $total_pesanan }}</h3></div>
        <div class="stat-card"><h6>Total Ahli</h6><h3>{{ $total_ahli }}</h3></div>
        <div class="stat-card"><h6>Total Transaksi</h6><h3>Rp {{ number_format($total_transaksi, 0, ',', '.') }}</h3></div>
        <div class="stat-card"><h6>Pengguna Aktif</h6><h3>{{ $pengguna_aktif }}</h3></div>
    </div>

    <hr>

    <div class="card activity-card">
        <div class="card-header">Aktivitas Terbaru</div>
        <div class="card-body">
            <table class="table">
                <thead><tr><th>Waktu</th><th>Pesanan</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($aktivitas_terbaru as $pesanan)
                        <tr>
                            <td>{{ $pesanan->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $pesanan->petani->name ?? 'Petani' }} → {{ $pesanan->pedagang->name ?? 'Pedagang' }}</td>
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
