@extends('layouts.app')
@section('title', 'Dashboard Ahli')

@section('content')
    <h2 class="page-title">Dashboard</h2>

    <div class="stat-row">
        <div class="stat-card"><h6>Keluhan Belum Terjawab</h6><h3>{{ $keluhan_belum_terjawab }}</h3></div>
        <div class="stat-card"><h6>Keluhan Terjawab</h6><h3>{{ $keluhan_terjawab }}</h3></div>
    </div>

    <hr>

    <div class="card activity-card">
        <div class="card-header">Aktivitas Terbaru</div>
        <div class="card-body">
            <table class="table">
                <thead><tr><th>Waktu</th><th>Konsultasi</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($aktivitas_terbaru as $konsultasi)
                        <tr>
                            <td>{{ $konsultasi->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $konsultasi->judul }} — {{ $konsultasi->petani->name ?? 'Petani' }}</td>
                            <td><span class="badge bg-{{ $konsultasi->status === 'answered' ? 'success' : 'warning' }}">{{ ucfirst($konsultasi->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
