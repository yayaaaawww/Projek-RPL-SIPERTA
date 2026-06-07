<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use App\Models\Panen;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetaniController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $totalTanaman = Tanaman::where('user_id', $user->id)->count();
        $totalPanen = Panen::whereHas('tanaman', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $totalTransaksi = Pesanan::where('petani_id', $user->id)->count();
        $totalPendapatan = Pesanan::where('petani_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_harga');

        $aktivitasTerbaru = Pesanan::where('petani_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'user'             => $user,
            'total_tanaman'    => $totalTanaman,
            'total_panen'      => $totalPanen,
            'total_transaksi'  => $totalTransaksi,
            'total_pendapatan' => $totalPendapatan,
            'aktivitas_terbaru' => $aktivitasTerbaru,
        ]);
    }
}