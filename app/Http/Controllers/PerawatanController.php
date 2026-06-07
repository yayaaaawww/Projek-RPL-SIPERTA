<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerawatanController extends Controller
{
    public function index(Tanaman $tanaman)
    {
        if ($tanaman->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($tanaman->perawatan()->latest()->get());
    }

    public function store(Request $request, Tanaman $tanaman)
    {
        if ($tanaman->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'tanggal_perawatan' => 'required|date',
            'penyiraman'        => 'boolean',
            'pemupukan'         => 'boolean',
            'penyiangan'        => 'boolean',
            'pestisida'         => 'boolean',
            'catatan'           => 'nullable|string',
            'foto'              => 'nullable|image|max:5120',
        ]);

        // Cek laporan hari ini sudah ada
        $existing = Perawatan::where('tanaman_id', $tanaman->id)
            ->where('tanggal_perawatan', $request->tanggal_perawatan)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Laporan hari ini sudah tersedia'], 422);
        }

        // Validasi minimal satu kegiatan diceklis
        if (!$request->penyiraman && !$request->pemupukan && 
            !$request->penyiangan && !$request->pestisida) {
            return response()->json(['message' => 'Pilih minimal satu kegiatan sebelum simpan'], 422);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('perawatan', 'public');
        }

        $perawatan = Perawatan::create([
            'tanaman_id'        => $tanaman->id,
            'tanggal_perawatan' => $request->tanggal_perawatan,
            'penyiraman'        => $request->penyiraman ?? false,
            'pemupukan'         => $request->pemupukan ?? false,
            'penyiangan'        => $request->penyiangan ?? false,
            'pestisida'         => $request->pestisida ?? false,
            'catatan'           => $request->catatan,
            'foto'              => $fotoPath,
            'status'            => 'submitted',
        ]);

        return response()->json([
            'message'   => 'Laporan perawatan berhasil disimpan',
            'perawatan' => $perawatan,
        ], 201);
    }

    public function show(Tanaman $tanaman, Perawatan $perawatan)
    {
        if ($tanaman->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($perawatan);
    }

    public function update(Request $request, Tanaman $tanaman, Perawatan $perawatan)
    {
        if ($tanaman->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($perawatan->status === 'locked') {
            return response()->json(['message' => 'Laporan sudah terkunci, tidak bisa diedit'], 403);
        }

        $perawatan->update($request->only([
            'penyiraman', 'pemupukan', 'penyiangan', 'pestisida', 'catatan'
        ]));

        return response()->json([
            'message'   => 'Perawatan berhasil diupdate',
            'perawatan' => $perawatan,
        ]);
    }

    public function destroy(Tanaman $tanaman, Perawatan $perawatan)
    {
        if ($tanaman->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($perawatan->status === 'locked') {
            return response()->json(['message' => 'Laporan sudah terkunci, tidak bisa dihapus'], 403);
        }

        $perawatan->delete();

        return response()->json(['message' => 'Perawatan berhasil dihapus']);
    }
}