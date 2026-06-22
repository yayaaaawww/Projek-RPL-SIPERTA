@extends('layouts.app')
@section('title', 'Pesanan Masuk')

@section('content')
    <h2 class="page-title">Pesanan Masuk</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @forelse ($pesanan as $p)
        <div class="card mb-3 p-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <strong>{{ $p->panen->nama_komoditas ?? 'Produk' }}</strong>
                    @php $map = ['pending'=>'warning','validated'=>'info','completed'=>'success','rejected'=>'danger']; @endphp
                    <span class="badge bg-{{ $map[$p->status] ?? 'secondary' }}">
                        {{ $p->status === 'pending' ? 'Menunggu Konfirmasi' : ucfirst($p->status) }}
                    </span>
                    <div class="text-muted" style="font-size:13px;">
                        {{ $p->created_at->format('d M Y, H:i') }}
                    </div>
                    <p class="mb-1 mt-2">
                        Pembeli: <strong>{{ $p->pedagang->name ?? '-' }}</strong><br>
                        Jumlah: {{ $p->jumlah_beli }} kg &middot;
                        Total: <strong>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong>
                    </p>
                    @if ($p->catatan)
                        <p class="mb-1" style="font-size:13px;"><em>Catatan: {{ $p->catatan }}</em></p>
                    @endif
                    @if ($p->bukti_transfer)
                        <a href="{{ asset('storage/' . $p->bukti_transfer) }}" target="_blank">
                            <i class="bi bi-receipt"></i> Lihat Bukti Transfer
                        </a>
                    @endif
                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if ($p->status === 'pending')
                        <form action="{{ route('petani.pesanan.status', $p->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="validated">
                            <button class="btn btn-sm btn-success" onsubmit="return confirm('Terima pembayaran ini?')">Terima</button>
                        </form>
                        <form action="{{ route('petani.pesanan.status', $p->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Tolak pesanan ini?')">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-sm btn-danger">Tolak</button>
                        </form>
                    @elseif ($p->status === 'validated')
                        <a href="{{ route('transaksi.chat', $p->id) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-chat-dots"></i> Chat
                        </a>
                        <form action="{{ route('petani.pesanan.status', $p->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button class="btn btn-sm btn-success">Tandai Selesai</button>
                        </form>
                    @elseif ($p->status === 'completed')
                        <a href="{{ route('transaksi.chat', $p->id) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-chat-dots"></i> Chat
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card p-3"><p class="text-muted mb-0">Belum ada pesanan masuk.</p></div>
    @endforelse
@endsection
