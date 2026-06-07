<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPesanan    = Pesanan::count();
        $totalAhli       = User::where('role', 'ahli')->count();
        $totalTransaksi  = Pesanan::where('status', 'completed')->sum('total_harga');
        $penggunaAktif   = User::where('status', 'aktif')->count();

        $aktivitasTerbaru = Pesanan::with(['petani', 'pedagang'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'total_pesanan'    => $totalPesanan,
            'total_ahli'       => $totalAhli,
            'total_transaksi'  => $totalTransaksi,
            'pengguna_aktif'   => $penggunaAktif,
            'aktivitas_terbaru' => $aktivitasTerbaru,
        ]);
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')
            ->latest()
            ->get();

        return response()->json($users);
    }

    public function blokir(Request $request, User $user)
    {
        // Cek admin blokir diri sendiri
        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'Admin tidak dapat memblokir akun sendiri'], 422);
        }

        // Cek udah suspended
        if ($user->status === 'suspended') {
            return response()->json(['message' => 'Akun sudah dalam status suspended'], 422);
        }

        $request->validate([
            'alasan' => 'required|string',
        ]);

        $user->update(['status' => 'suspended']);

        return response()->json([
            'message' => 'Akun berhasil diblokir',
            'user'    => $user,
        ]);
    }

    public function unblokir(User $user)
    {
        $user->update(['status' => 'aktif']);

        return response()->json([
            'message' => 'Akun berhasil di-unblokir',
            'user'    => $user,
        ]);
    }
}