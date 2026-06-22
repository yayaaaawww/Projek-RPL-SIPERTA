<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use App\Models\Perawatan;
use App\Models\Panen;
use App\Models\Pesanan;
use App\Models\Konsultasi;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PetaniController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $totalTanaman = Tanaman::where('user_id', $user->id)->count();
        $totalPanen = Panen::whereHas('tanaman', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $totalTransaksi = Pesanan::where('petani_id', $user->id)->count();
        $totalPendapatan = Pesanan::where('petani_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_harga');

        $aktivitasTerbaru = Pesanan::where('petani_id', $user->id)
            ->with(['panen', 'pedagang'])
            ->latest()
            ->take(5)
            ->get();

        return view('petani.dashboard', [
            'user'              => $user,
            'total_tanaman'     => $totalTanaman,
            'total_panen'       => $totalPanen,
            'total_transaksi'   => $totalTransaksi,
            'total_pendapatan'  => $totalPendapatan,
            'aktivitas_terbaru' => $aktivitasTerbaru,
        ]);
    }

    // ===== Data Pertanian (Tanaman) =====
    public function dataPertanian()
    {
        $tanaman = Tanaman::where('user_id', Auth::id())->latest()->get();

        return view('petani.data', ['tanaman' => $tanaman]);
    }

    public function storeTanaman(Request $request)
    {
        $request->validate([
            'jenis_tanaman' => 'required|string',
            'nama_lahan'    => 'nullable|string',
            'alamat_lahan'  => 'nullable|string',
        ]);

        Tanaman::create([
            'user_id'       => Auth::id(),
            'jenis_tanaman' => $request->jenis_tanaman,
            'nama_lahan'    => $request->nama_lahan,
            'alamat_lahan'  => $request->alamat_lahan,
            'status'        => 'aktif',
        ]);

        return redirect()->route('petani.data')->with('success', 'Data pertanian berhasil ditambahkan.');
    }

    public function destroyTanaman(Tanaman $tanaman)
    {
        if ($tanaman->user_id !== Auth::id()) {
            abort(403);
        }

        $tanaman->delete();

        return redirect()->route('petani.data')->with('success', 'Data pertanian berhasil dihapus.');
    }

    // ===== Log Harian / Perawatan (FR-04 Laporan Harian) =====
    public function perawatan(Tanaman $tanaman)
    {
        if ($tanaman->user_id !== Auth::id()) {
            abort(403);
        }

        $perawatan = $tanaman->perawatan()->latest('tanggal_perawatan')->get();

        return view('petani.perawatan', [
            'tanaman'   => $tanaman,
            'perawatan' => $perawatan,
        ]);
    }

    public function storePerawatan(Request $request, Tanaman $tanaman)
    {
        if ($tanaman->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'tanggal_perawatan' => 'required|date',
            'catatan'           => 'nullable|string',
        ]);

        // Satu laporan per tanggal (VR-02 di SRS)
        $sudahAda = Perawatan::where('tanaman_id', $tanaman->id)
            ->where('tanggal_perawatan', $request->tanggal_perawatan)
            ->exists();

        if ($sudahAda) {
            return back()->withErrors(['tanggal_perawatan' => 'Laporan untuk tanggal ini sudah ada.'])->withInput();
        }

        // Minimal satu kegiatan diceklis (VR-01 di SRS)
        if (! $request->penyiraman && ! $request->pemupukan && ! $request->penyiangan && ! $request->pestisida) {
            return back()->withErrors(['kegiatan' => 'Pilih minimal satu kegiatan sebelum simpan.'])->withInput();
        }

        Perawatan::create([
            'tanaman_id'        => $tanaman->id,
            'tanggal_perawatan' => $request->tanggal_perawatan,
            'penyiraman'        => $request->boolean('penyiraman'),
            'pemupukan'         => $request->boolean('pemupukan'),
            'penyiangan'        => $request->boolean('penyiangan'),
            'pestisida'         => $request->boolean('pestisida'),
            'catatan'           => $request->catatan,
            'status'            => 'submitted',
        ]);

        return redirect()->route('petani.perawatan', $tanaman->id)
            ->with('success', 'Log harian berhasil disimpan.');
    }

    // ===== Update Panen / Katalog (FR-07) =====
    public function katalog()
    {
        $panen = Panen::whereHas('tanaman', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with('tanaman')
            ->latest()
            ->get();

        $tanaman = Tanaman::where('user_id', Auth::id())->get();

        return view('petani.katalog', [
            'panen'   => $panen,
            'tanaman' => $tanaman,
        ]);
    }

    public function storePanen(Request $request)
    {
        $request->validate([
            'tanaman_id'     => 'required|exists:tanaman,id',
            'nama_komoditas' => 'required|string',
            'jumlah_kg'      => 'required|numeric|min:0.1',
            'harga_per_kg'   => 'required|numeric|min:0',
            'foto'           => 'required|image|max:5120',
            'lokasi_lahan'   => 'nullable|string',
        ]);

        // Pastikan tanaman milik petani ini
        $tanaman = Tanaman::where('id', $request->tanaman_id)
            ->where('user_id', Auth::id())
            ->first();

        if (! $tanaman) {
            return back()->withErrors(['tanaman_id' => 'Tanaman tidak valid.'])->withInput();
        }

        $fotoPath = $request->file('foto')->store('panen', 'public');

        Panen::create([
            'tanaman_id'     => $request->tanaman_id,
            'nama_komoditas' => $request->nama_komoditas,
            'jumlah_kg'      => $request->jumlah_kg,
            'harga_per_kg'   => $request->harga_per_kg,
            'foto'           => $fotoPath,
            'lokasi_lahan'   => $request->lokasi_lahan,
            'status'         => 'available',
        ]);

        return redirect()->route('petani.katalog')->with('success', 'Hasil panen berhasil ditambahkan ke katalog.');
    }

    public function updatePanen(Request $request, Panen $panen)
    {
        if ($panen->tanaman->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_komoditas' => 'required|string',
            'jumlah_kg'      => 'required|numeric|min:0',
            'harga_per_kg'   => 'required|numeric|min:0',
            'status'         => 'required|in:available,sold_out,archived',
            'foto'           => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['nama_komoditas', 'jumlah_kg', 'harga_per_kg', 'status']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('panen', 'public');
        }

        $panen->update($data);

        return redirect()->route('petani.katalog')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyPanen(Panen $panen)
    {
        if ($panen->tanaman->user_id !== Auth::id()) {
            abort(403);
        }

        $panen->delete();

        return redirect()->route('petani.katalog')->with('success', 'Produk berhasil dihapus dari katalog.');
    }

    // ===== Konsultasi (FR-05) =====
    public function konsultasi()
    {
        $konsultasi = Konsultasi::where('petani_id', Auth::id())
            ->with('ahli')
            ->latest()
            ->get();

        return view('petani.konsultasi', ['konsultasi' => $konsultasi]);
    }

    public function storeKonsultasi(Request $request)
    {
        $request->validate([
            'judul'            => 'required|string',
            'deskripsi'        => 'required|string|min:10',
            'foto'             => 'required|image|max:5120',
            'kategori_tanaman' => 'nullable|string',
        ]);

        $fotoPath = $request->file('foto')->store('konsultasi', 'public');

        Konsultasi::create([
            'petani_id'        => Auth::id(),
            'judul'            => $request->judul,
            'deskripsi'        => $request->deskripsi,
            'foto'             => $fotoPath,
            'kategori_tanaman' => $request->kategori_tanaman,
            'status'           => 'sent',
        ]);

        return redirect()->route('petani.konsultasi')->with('success', 'Konsultasi berhasil dikirim ke ahli.');
    }

    public function destroyKonsultasi(Konsultasi $konsultasi)
    {
        if ($konsultasi->petani_id !== Auth::id()) {
            abort(403);
        }

        // Chat ikut terhapus otomatis (onDelete cascade)
        $konsultasi->delete();

        return redirect()->route('petani.konsultasi')->with('success', 'Konsultasi & chat berhasil dihapus.');
    }

    // ===== Laporan (FR-03) =====
    public function laporan()
    {
        $laporan = Laporan::where('pelapor_id', Auth::id())
            ->with('terlapor')
            ->latest()
            ->get();

        // Daftar user yang bisa dilaporkan (selain diri sendiri & admin)
        $users = User::where('id', '!=', Auth::id())
            ->where('role', '!=', 'admin')
            ->get();

        return view('petani.laporan', [
            'laporan' => $laporan,
            'users'   => $users,
        ]);
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'terlapor_id' => 'required|exists:users,id',
            'alasan'      => 'required|string',
            'bukti'       => 'nullable|image|max:5120',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('laporan', 'public');
        }

        Laporan::create([
            'pelapor_id'    => Auth::id(),
            'terlapor_id'   => $request->terlapor_id,
            'jenis_pelapor' => Auth::user()->role,
            'alasan'        => $request->alasan,
            'bukti'         => $buktiPath,
            'status'        => 'pending',
        ]);

        return redirect()->route('petani.laporan')->with('success', 'Laporan berhasil dikirim ke admin.');
    }

    // ===== Profile =====
    public function profile()
    {
        return view('petani.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'no_hp'       => 'nullable|string',
            'alamat'      => 'nullable|string',
            'no_rekening' => 'nullable|string',
            'password'    => 'nullable|min:8|confirmed',
        ]);

        $user->name        = $request->name;
        $user->email       = $request->email;
        $user->no_hp       = $request->no_hp;
        $user->alamat      = $request->alamat;
        $user->no_rekening = $request->no_rekening;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('petani.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    // ===== Konfirmasi Pesanan / Pembayaran (FR-10) =====
    public function pesananMasuk()
    {
        $pesanan = Pesanan::where('petani_id', Auth::id())
            ->with(['panen', 'pedagang'])
            ->latest()
            ->get();

        return view('petani.pesanan', ['pesanan' => $pesanan]);
    }

    public function konfirmasiPesanan(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->petani_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:validated,rejected,completed',
        ]);

        $status = $request->status;

        // Saat pertama kali diterima (pending -> validated): potong stok
        if ($status === 'validated' && $pesanan->status === 'pending') {
            $panen = $pesanan->panen;
            if ($panen) {
                $sisaStok = $panen->jumlah_kg - $pesanan->jumlah_beli;
                $panen->update([
                    'jumlah_kg' => max($sisaStok, 0),
                    'status'    => $sisaStok <= 0 ? 'sold_out' : 'available',
                ]);
            }
        }

        $pesanan->update(['status' => $status]);

        $pesan = [
            'validated' => 'Pembayaran dikonfirmasi & stok diperbarui.',
            'rejected'  => 'Pesanan ditolak.',
            'completed' => 'Pesanan ditandai selesai.',
        ][$status];

        return back()->with('success', $pesan);
    }
}
