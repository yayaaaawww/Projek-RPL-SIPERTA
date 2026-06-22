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
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal_perawatan"
                               value="{{ old('tanggal_perawatan', date('Y-m-d')) }}" required>
                    </div>

                    <label class="form-label">Kegiatan yang dilakukan:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="penyiraman" value="1" id="penyiraman" {{ old('penyiraman') ? 'checked' : '' }}>
                        <label class="form-check-label" for="penyiraman">Penyiraman</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pemupukan" value="1" id="pemupukan" {{ old('pemupukan') ? 'checked' : '' }}>
                        <label class="form-check-label" for="pemupukan">Pemupukan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="penyiangan" value="1" id="penyiangan" {{ old('penyiangan') ? 'checked' : '' }}>
                        <label class="form-check-label" for="penyiangan">Penyiangan</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="pestisida" value="1" id="pestisida" {{ old('pestisida') ? 'checked' : '' }}>
                        <label class="form-check-label" for="pestisida">Pemberian Pestisida</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea>
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
                    <table class="table">
                        <thead>
                            <tr><th>Tanggal</th><th>Kegiatan</th><th>Catatan</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($perawatan as $p)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_perawatan)->format('d M Y') }}</td>
                                    <td>
                                        @if ($p->penyiraman) <span class="badge bg-info">Siram</span> @endif
                                        @if ($p->pemupukan) <span class="badge bg-success">Pupuk</span> @endif
                                        @if ($p->penyiangan) <span class="badge bg-warning text-dark">Siang</span> @endif
                                        @if ($p->pestisida) <span class="badge bg-danger">Pestisida</span> @endif
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
@endsection
