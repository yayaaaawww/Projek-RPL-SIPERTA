<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AhliController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $keluhanBelumTerjawab = Konsultasi::where('status', 'sent')
            ->count();

        $keluhanTerjawab = Konsultasi::where('ahli_id', $user->id)
            ->where('status', 'answered')
            ->count();

        $aktivitasTerbaru = Konsultasi::where('ahli_id', $user->id)
            ->with('petani')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'user'                    => $user,
            'keluhan_belum_terjawab'  => $keluhanBelumTerjawab,
            'keluhan_terjawab'        => $keluhanTerjawab,
            'aktivitas_terbaru'       => $aktivitasTerbaru,
        ]);
    }
}