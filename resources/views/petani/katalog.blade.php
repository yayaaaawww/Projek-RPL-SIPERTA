@extends('layouts.app')
@section('title', 'Katalog Penjualan')

@section('content')
    <h2 class="page-title">Katalog Penjualan</h2>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @if ($tanaman->isEmpty())
        <div class="alert alert-warning">
            Kamu belum punya data tanaman. Tambah dulu di
            <a href="{{ route('petani.data') }}">Data Pertanian</a> sebelum menjual hasil panen.
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        @forelse ($panen as $p)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if ($p->foto)
                        <img src="{{ asset('storage/' . $p->foto) }}" alt="{{ $p->nama_komoditas }}"
                             class="card-img-top" style="height:150px; object-fit:cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:150px;">
                            <i class="bi bi-card-image" style="font-size:42px; color:#bbb;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold mb-1">{{ $p->nama_komoditas }}</h6>
                        <p class="mb-2 text-muted" style="font-size:13px;">
                            {{ rtrim(rtrim(number_format($p->jumlah_kg, 2, '.', ''), '0'), '.') }} kg
                            &middot; Rp {{ number_format($p->harga_per_kg, 0, ',', '.') }}/kg
                        </p>
                        <span class="badge bg-{{ $p->status === 'available' ? 'success' : 'secondary' }} mb-3" style="width:fit-content;">
                            {{ ucfirst($p->status) }}
                        </span>

                        <div class="mt-auto d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal" data-bs-target="#editPanen{{ $p->id }}">Update</button>
                            <form action="{{ route('petani.katalog.destroy', $p->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus produk ini dari katalog?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Edit Panen --}}
            <div class="modal fade" id="editPanen{{ $p->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('petani.katalog.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Nama Komoditas</label>
                                    <input type="text" class="form-control" name="nama_komoditas" value="{{ $p->nama_komoditas }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Jumlah (kg)</label>
                                        <input type="number" step="0.1" min="0" class="form-control" name="jumlah_kg" value="{{ $p->jumlah_kg }}" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Harga / kg</label>
                                        <input type="number" min="0" class="form-control" name="harga_per_kg" value="{{ $p->harga_per_kg }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="available" @selected($p->status == 'available')>Tersedia</option>
                                        <option value="sold_out"  @selected($p->status == 'sold_out')>Habis</option>
                                        <option value="archived"  @selected($p->status == 'archived')>Arsip</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ganti Foto (opsional)</label>
                                    <input type="file" class="form-control" name="foto" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse

        {{-- Card tambah --}}
        @if ($tanaman->isNotEmpty())
            <div class="col">
                <div class="card h-100 d-flex align-items-center justify-content-center"
                     style="min-height:240px; cursor:pointer; border:2px dashed #ccc;"
                     data-bs-toggle="modal" data-bs-target="#tambahPanen">
                    <i class="bi bi-plus-lg" style="font-size:46px; color:#888;"></i>
                </div>
            </div>
        @endif
    </div>

    @if ($panen->isEmpty())
        <p class="text-muted mt-3">Belum ada hasil panen yang dijual.</p>
    @endif

    {{-- Modal Tambah Panen --}}
    <div class="modal fade" id="tambahPanen" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Hasil Panen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('petani.katalog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Dari Lahan / Tanaman <span class="text-danger">*</span></label>
                            <select class="form-select" name="tanaman_id" required>
                                <option value="">-- Pilih tanaman --</option>
                                @foreach ($tanaman as $t)
                                    <option value="{{ $t->id }}">{{ $t->jenis_tanaman }} ({{ $t->nama_lahan ?? 'tanpa nama' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Komoditas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_komoditas" value="{{ old('nama_komoditas') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Jumlah (kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" min="0.1" class="form-control" name="jumlah_kg" value="{{ old('jumlah_kg') }}" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Harga / kg <span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control" name="harga_per_kg" value="{{ old('harga_per_kg') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Produk <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="foto" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi Lahan (opsional)</label>
                            <input type="text" class="form-control" name="lokasi_lahan" value="{{ old('lokasi_lahan') }}">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Terbitkan ke Katalog</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
