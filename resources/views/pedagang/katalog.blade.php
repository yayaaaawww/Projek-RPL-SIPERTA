@extends('layouts.app')
@section('title', 'Katalog')

@section('content')
    <h2 class="page-title">Katalog Hasil Panen</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    {{-- Pencarian --}}
    <form method="GET" action="{{ route('pedagang.katalog') }}" class="mb-3 d-flex gap-2" style="max-width:400px;">
        <input type="text" name="cari" class="form-control" placeholder="Cari komoditas..." value="{{ $cari }}">
        <button class="btn btn-success">Cari</button>
    </form>

    <div class="katalog-grid">
        @forelse ($produk as $p)
            <div class="produk-card">
                @if ($p->foto)
                    <img src="{{ asset('storage/' . $p->foto) }}" alt="{{ $p->nama_komoditas }}"
                         style="width:100%; height:110px; object-fit:cover; border-radius:10px;">
                @else
                    <i class="bi bi-card-image produk-icon"></i>
                @endif

                <h6 class="mt-2 mb-0">{{ $p->nama_komoditas }}</h6>
                <small class="text-muted">oleh {{ $p->tanaman->petani->name ?? 'Petani' }}</small>
                <p class="mb-1 mt-1" style="font-size:13px;">
                    Stok {{ $p->jumlah_kg }} kg<br>
                    <strong>Rp {{ number_format($p->harga_per_kg, 0, ',', '.') }}/kg</strong>
                </p>

                <button type="button" class="btn btn-sm btn-success"
                        data-bs-toggle="modal" data-bs-target="#beli{{ $p->id }}">Beli</button>
            </div>

            {{-- Modal Beli --}}
            <div class="modal fade" id="beli{{ $p->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Beli {{ $p->nama_komoditas }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">
                                Harga Rp {{ number_format($p->harga_per_kg, 0, ',', '.') }}/kg &middot;
                                stok {{ $p->jumlah_kg }} kg<br>
                                dari <strong>{{ $p->tanaman->petani->name ?? 'Petani' }}</strong>
                            </p>
                            <form action="{{ route('pedagang.katalog.beli', $p->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Beli (kg) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" min="0.1" max="{{ $p->jumlah_kg }}" class="form-control" name="jumlah_beli" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bukti Transfer <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="bukti_transfer" accept="image/*" required>
                                    <small class="text-muted">Upload foto bukti transfer ke petani.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catatan (opsional)</label>
                                    <textarea class="form-control" name="catatan" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Kirim Pesanan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada produk yang tersedia{{ $cari ? ' untuk "'.$cari.'"' : '' }}.</p>
        @endforelse
    </div>
@endsection
