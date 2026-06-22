<?php

namespace App\Http\Controllers;

use App\Models\Panen;
use App\Models\Pesanan;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PedagangController extends Controller
{
    public function dashboard()
    {
        $uid = Auth::id();

        $totalPesanan = Pesanan::where('pedagang_id', $uid)->count();
        $menunggu     = Pesanan::where('pedagang_id', $uid)->where('status', 'pending')->count();
        $diproses     = Pesanan::where('pedagang_id', $uid)->where('status', 'validated')->count();
        $selesai      = Pesanan::where('pedagang_id', $uid)->where('status', 'completed')->count();

        $aktivitasTerbaru = Pesanan::where('pedagang_id', $uid)
            ->with(['panen', 'petani'])
            ->latest()
            ->take(5)
            ->get();

        return view('pedagang.dashboard', [
            'total_pesanan'     => $totalPesanan,
            'menunggu'          => $menunggu,
            'diproses'          => $diproses,
            'selesai'           => $selesai,
            'aktivitas_terbaru' => $aktivitasTerbaru,
        ]);
    }

    // ===== Katalog (FR-08) =====
    public function katalog(Request $request)
    {
        $query = Panen::where('status', 'available')
            ->with(['tanaman.petani']);

        // Pencarian komoditas
        if ($request->filled('cari')) {
            $query->where('nama_komoditas', 'like', '%' . $request->cari . '%');
        }

        $produk = $query->latest()->get()
            // Sembunyikan produk dari petani yang diblokir (VR-04)
            ->filter(function ($p) {
                return optional($p->tanaman->petani)->status !== 'suspended';
            });

        return view('pedagang.katalog', [
            'produk' => $produk,
            'cari'   => $request->cari,
        ]);
    }

    // ===== Transaksi Beli (FR-09) =====
    public function beli(Request $request, Panen $panen)
    {
        $request->validate([
            'jumlah_beli'    => 'required|numeric|min:0.1',
            'bukti_transfer' => 'required|image|max:5120',
            'catatan'        => 'nullable|string',
        ]);

        if ($panen->status !== 'available') {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        if ($request->jumlah_beli > $panen->jumlah_kg) {
            return back()->withErrors(['jumlah_beli' => 'Maaf, stok tidak mencukupi (tersisa ' . $panen->jumlah_kg . ' kg).'])->withInput();
        }

        $buktiPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Pesanan::create([
            'panen_id'       => $panen->id,
            'pedagang_id'    => Auth::id(),
            'petani_id'      => $panen->tanaman->user_id,
            'jumlah_beli'    => $request->jumlah_beli,
            'total_harga'    => $request->jumlah_beli * $panen->harga_per_kg,
            'bukti_transfer' => $buktiPath,
            'catatan'        => $request->catatan,
            'status'         => 'pending',
        ]);

        return redirect()->route('pedagang.pesanan')->with('success', 'Pesanan dikirim, menunggu konfirmasi petani.');
    }

    // ===== Daftar Pesanan Pedagang =====
    public function pesanan()
    {
        $pesanan = Pesanan::where('pedagang_id', Auth::id())
            ->with(['panen', 'petani'])
            ->latest()
            ->get();

        return view('pedagang.pesanan', ['pesanan' => $pesanan]);
    }

    // ===== Laporan (FR-03) =====
    public function laporan()
    {
        $laporan = Laporan::where('pelapor_id', Auth::id())
            ->with('terlapor')
            ->latest()
            ->get();

        $users = User::where('id', '!=', Auth::id())
            ->where('role', '!=', 'admin')
            ->get();

        return view('pedagang.laporan', [
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

        return redirect()->route('pedagang.laporan')->with('success', 'Laporan berhasil dikirim ke admin.');
    }

    // ===== Profile =====
    public function profile()
    {
        return view('pedagang.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'no_hp'    => 'nullable|string',
            'alamat'   => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name   = $request->name;
        $user->email  = $request->email;
        $user->no_hp  = $request->no_hp;
        $user->alamat = $request->alamat;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('pedagang.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
