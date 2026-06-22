@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
    <h2 class="page-title">Laporan Akun</h2>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        {{-- Form lapor --}}
        <div class="col-md-5 mb-4">
            <h5 class="mb-3">Buat Laporan</h5>
            <div class="card p-3">
                <form action="{{ route('ahli.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Akun yang Dilaporkan <span class="text-danger">*</span></label>
                        <select class="form-select" name="terlapor_id" required>
                            <option value="">-- Pilih akun --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alasan" rows="3" required>{{ old('alasan') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti (opsional)</label>
                        <input type="file" class="form-control" name="bukti" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Kirim Laporan</button>
                </form>
            </div>
        </div>

        {{-- Riwayat laporan --}}
        <div class="col-md-7 mb-4">
            <h5 class="mb-3">Riwayat Laporan</h5>
            <div class="card p-3">
                @forelse ($laporan as $l)
                    <div class="d-flex justify-content-between align-items-start {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                        <div>
                            <strong>{{ $l->terlapor->name ?? '-' }}</strong>
                            <span class="badge bg-light text-dark border">{{ ucfirst($l->terlapor->role ?? '-') }}</span>
                            <div class="text-muted" style="font-size:12px;">{{ $l->created_at->format('d M Y, H:i') }}</div>
                            <p class="mb-0 mt-1">{{ $l->alasan }}</p>
                        </div>
                        <span class="badge bg-{{ $l->status === 'resolved' ? 'success' : 'warning' }} flex-shrink-0">
                            {{ $l->status === 'resolved' ? 'Selesai' : 'Menunggu' }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada laporan.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
