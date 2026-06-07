<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $laporan = Laporan::with(['pelapor', 'terlapor', 'admin'])
                ->latest()
                ->get();
        } else {
            $laporan = Laporan::where('pelapor_id', $user->id)
                ->with(['terlapor'])
                ->latest()
                ->get();
        }

        return response()->json($laporan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'terlapor_id'  => 'required|exists:users,id',
            'jenis_pelapor'=> 'required|in:petani,ahli,pedagang',
            'alasan'       => 'required|string',
            'bukti'        => 'nullable|image|max:5120',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('laporan', 'public');
        }

        $laporan = Laporan::create([
            'pelapor_id'   => Auth::id(),
            'terlapor_id'  => $request->terlapor_id,
            'jenis_pelapor'=> $request->jenis_pelapor,
            'alasan'       => $request->alasan,
            'bukti'        => $buktiPath,
            'status'       => 'pending',
        ]);

        return response()->json([
            'message' => 'Laporan berhasil dikirim',
            'laporan' => $laporan,
        ], 201);
    }

    public function resolve(Request $request, Laporan $laporan)
    {
        $laporan->update([
            'admin_id' => Auth::id(),
            'status'   => 'resolved',
        ]);

        return response()->json([
            'message' => 'Laporan berhasil diselesaikan',
            'laporan' => $laporan,
        ]);
    }
}
