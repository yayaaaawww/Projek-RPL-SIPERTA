@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
    <h2 class="page-title">Pesanan Saya</h2>

    <div class="card activity-card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr><th>Tanggal</th><th>Produk</th><th>Jumlah</th><th>Total</th><th>Petani</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d M Y') }}</td>
                            <td>{{ $p->panen->nama_komoditas ?? '-' }}</td>
                            <td>{{ $p->jumlah_beli }} kg</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $p->petani->name ?? '-' }}</td>
                            <td>
                                @php $map = ['pending'=>'warning','validated'=>'info','completed'=>'success','rejected'=>'danger']; @endphp
                                <span class="badge bg-{{ $map[$p->status] ?? 'secondary' }}">
                                    {{ $p->status === 'pending' ? 'Menunggu Konfirmasi' : ucfirst($p->status) }}
                                </span>
                                @if (in_array($p->status, ['validated', 'completed']))
                                    <a href="{{ route('transaksi.chat', $p->id) }}" class="btn btn-sm btn-outline-success ms-1">
                                        <i class="bi bi-chat-dots"></i> Chat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
