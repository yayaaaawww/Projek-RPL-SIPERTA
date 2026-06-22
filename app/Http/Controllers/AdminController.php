<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Laporan;
use App\Models\Konsultasi;
use App\Models\ChatKonsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPesanan   = Pesanan::count();
        $totalAhli      = User::where('role', 'ahli')->count();
        $totalTransaksi = Pesanan::where('status', 'completed')->sum('total_harga');
        $penggunaAktif  = User::where('status', 'aktif')->count();

        $aktivitasTerbaru = Pesanan::with(['petani', 'pedagang'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'total_pesanan'     => $totalPesanan,
            'total_ahli'        => $totalAhli,
            'total_transaksi'   => $totalTransaksi,
            'pengguna_aktif'    => $penggunaAktif,
            'aktivitas_terbaru' => $aktivitasTerbaru,
        ]);
    }

    // ===== Kelola Pengguna (FR-02 & FR-03) =====
    public function users()
    {
        $users = User::where('role', '!=', 'admin')
            ->latest()
            ->get();

        return view('admin.users', ['users' => $users]);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'role'     => 'required|in:petani,ahli,pedagang',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'status'   => 'aktif',
        ]);

        return back()->with('success', 'Akun baru berhasil dibuat.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:petani,ahli,pedagang',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return back()->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        try {
            $user->delete();
            return back()->with('success', 'Akun "' . $user->name . '" berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Akun tidak bisa dihapus karena masih punya data terkait (tanaman/pesanan/konsultasi). Blokir saja akunnya.');
        }
    }

    // ===== Profile Admin =====
    public function profile()
    {
        return view('admin.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function blokir(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Admin tidak dapat memblokir akun sendiri.');
        }
        if ($user->status === 'suspended') {
            return back()->with('error', 'Akun sudah diblokir.');
        }

        $request->validate(['alasan' => 'required|string']);

        $user->update(['status' => 'suspended']);

        return back()->with('success', 'Akun "' . $user->name . '" berhasil diblokir.');
    }

    public function unblokir(User $user)
    {
        $user->update(['status' => 'aktif']);

        return back()->with('success', 'Akun "' . $user->name . '" berhasil diaktifkan kembali.');
    }

    // ===== Laporan (admin lihat semua & selesaikan) =====
    public function laporan()
    {
        $laporan = Laporan::with(['pelapor', 'terlapor', 'admin'])
            ->latest()
            ->get();

        return view('admin.laporan', ['laporan' => $laporan]);
    }

    public function resolveLaporan(Laporan $laporan)
    {
        $laporan->update([
            'admin_id' => Auth::id(),
            'status'   => 'resolved',
        ]);

        return back()->with('success', 'Laporan ditandai selesai.');
    }

    public function blokirFromLaporan(Laporan $laporan)
    {
        $terlapor = $laporan->terlapor;

        if ($terlapor && $terlapor->status !== 'suspended') {
            $terlapor->update(['status' => 'suspended']);
        }

        $laporan->update([
            'admin_id' => Auth::id(),
            'status'   => 'resolved',
        ]);

        return back()->with('success', 'Akun terlapor diblokir & laporan diselesaikan.');
    }

    // ===== Pantau Konsultasi (admin baca chat ahli <-> petani) =====
    public function konsultasi()
    {
        $konsultasi = Konsultasi::with(['petani', 'ahli'])
            ->latest()
            ->get();

        return view('admin.konsultasi', ['konsultasi' => $konsultasi]);
    }

    public function chatKonsultasi(Konsultasi $konsultasi)
    {
        $chat = ChatKonsultasi::where('konsultasi_id', $konsultasi->id)
            ->with('pengirim')
            ->orderBy('created_at')
            ->get();

        $konsultasi->load(['petani', 'ahli']);

        return view('admin.chat-konsultasi', [
            'konsultasi' => $konsultasi,
            'chat'       => $chat,
        ]);
    }

    // ===== Transaksi (overview semua pesanan) =====
    public function transaksi()
    {
        $pesanan = Pesanan::with(['petani', 'pedagang', 'panen'])
            ->latest()
            ->get();

        return view('admin.transaksi', ['pesanan' => $pesanan]);
    }
}
