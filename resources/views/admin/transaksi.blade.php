@extends('layouts.app')
@section('title', 'Transaksi')

@section('content')
    <h2 class="page-title">Semua Transaksi</h2>

    <div class="card activity-card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr><th>Tanggal</th><th>Produk</th><th>Petani</th><th>Pedagang</th><th>Jumlah</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d M Y') }}</td>
                            <td>{{ $p->panen->nama_komoditas ?? '-' }}</td>
                            <td>{{ $p->petani->name ?? '-' }}</td>
                            <td>{{ $p->pedagang->name ?? '-' }}</td>
                            <td>{{ $p->jumlah_beli }} kg</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @php $map = ['pending'=>'warning','validated'=>'info','completed'=>'success','rejected'=>'danger']; @endphp
                                <span class="badge bg-{{ $map[$p->status] ?? 'secondary' }}">{{ ucfirst($p->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
