@extends('layouts.app')
@section('title', 'Log Harian')

@section('content')
    <a href="{{ route('petani.data') }}" class="text-decoration-none">&larr; Kembali ke Data Pertanian</a>

    <h2 class="page-title mt-2">
        Log Harian — {{ $tanaman->nama_lahan ?? $tanaman->jenis_tanaman }}
    </h2>
    <p class="text-muted">Jenis tanaman: {{ $tanaman->jenis_tanaman }}</p>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        {{-- Form input log hari ini --}}
        <div class="col-md-5 mb-4">
            <div class="card p-3">
                <h5 class="mb-3">Catat Kegiatan Hari Ini</h5>
                <form action="{{ route('petani.perawatan.store', $tanaman->id) }}" method="POST">
                    @csrf
                    
                    {{-- Input Tanggal --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal_perawatan"
                               value="{{ old('tanggal_perawatan', date('Y-m-d')) }}" required>
                    </div>
                   <div class="mb-3">
                        <label class="form-label font-weight-bold">Kegiatan yang dilakukan:</label>
                        
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="penyemaian" value="1" id="penyemaian" {{ old('penyemaian') ? 'checked' : '' }}>
                            <label class="form-check-label" for="penyemaian">Penyemaian Benih</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="penggemburan" value="1" id="penggemburan" {{ old('penggemburan') ? 'checked' : '' }}>
                            <label class="form-check-label" for="penggemburan">Penggemburan Tanah / Pembajakan</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="penanaman" value="1" id="penanaman" {{ old('penanaman') ? 'checked' : '' }}>
                            <label class="form-check-label" for="penanaman">Penanaman Bibit</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="penyiraman" value="1" id="penyiraman" {{ old('penyiraman') ? 'checked' : '' }}>
                            <label class="form-check-label" for="penyiraman">Penyiraman Tanaman</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="pemangkasan" value="1" id="pemangkasan" {{ old('pemangkasan') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pemangkasan">Pemangkasan Ranting / Daun</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="pemupukan" value="1" id="pemupukan" {{ old('pemupukan') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pemupukan">Pemupukan Utama</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="nutrisi" value="1" id="nutrisi" {{ old('nutrisi') ? 'checked' : '' }}>
                            <label class="form-check-label" for="nutrisi">Pemberian Nutrisi Tambahan / Vitamin</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="penyiangan" value="1" id="penyiangan" {{ old('penyiangan') ? 'checked' : '' }}>
                            <label class="form-check-label" for="penyiangan">Penyiangan (Pembersihan Gulma)</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="pestisida" value="1" id="pestisida" {{ old('pestisida') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pestisida">Pengendalian Hama / Pestisida</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="pemasangan_ajir" value="1" id="pemasangan_ajir" {{ old('pemasangan_ajir') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pemasangan_ajir">Pemasangan Ajir / Penyangga</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="pengecekan" value="1" id="pengecekan" {{ old('pengecekan') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pengecekan">Monitoring / Pengecekan Rutin</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="pemanenan" value="1" id="pemanenan" {{ old('pemanenan') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pemanenan">Pemanenan Hasil</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Simpan Log</button>
                </form>
            </div>
        </div>

        {{-- Riwayat log --}}
        <div class="col-md-7">
            <div class="card activity-card">
                <div class="card-header">Riwayat Log Harian</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr><th>Tanggal</th><th>Kegiatan</th><th>Catatan</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($perawatan as $p)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal_perawatan)->format('d M Y') }}</td>
                                        <td>
                                            @if ($p->penyemaian) <span class="badge bg-info">Semai</span> @endif
                                            @if ($p->penggemburan) <span class="badge bg-secondary">Gembur</span> @endif
                                            @if ($p->penanaman) <span class="badge bg-dark">Tanam</span> @endif
                                            @if ($p->penyiraman) <span class="badge bg-info">Siram</span> @endif
                                            @if ($p->pemangkasan) <span class="badge bg-info">Pangkas</span> @endif
                                            @if ($p->pemupukan) <span class="badge bg-success">Pupuk</span> @endif
                                            @if ($p->nutrisi) <span class="badge bg-success">Nutrisi</span> @endif
                                            @if ($p->penyiangan) <span class="badge bg-warning text-dark">Penyiangan</span> @endif
                                            @if ($p->pestisida) <span class="badge bg-danger">Pestisida</span> @endif
                                            @if ($p->pemasangan_ajir) <span class="badge bg-secondary">Ajir</span> @endif
                                            @if ($p->pengecekan) <span class="badge bg-warning text-dark">Cek</span> @endif
                                            @if ($p->pemanenan) <span class="badge bg-primary">Panen</span> @endif
                                        </td>
                                        <td>{{ $p->catatan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Belum ada log harian.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection