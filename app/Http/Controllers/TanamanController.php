<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TanamanController extends Controller
{
    public function index()
    {
        $tanaman = Tanaman::where('user_id', Auth::id())
            ->with('perawatan')
            ->latest()
            ->get();

        return response()->json($tanaman);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_tanaman' => 'required|string',
            'nama_lahan'    => 'nullable|string',
            'alamat_lahan'  => 'nullable|string',
        ]);

        $tanaman = Tanaman::create([
            'user_id'       => Auth::id(),
            'jenis_tanaman' => $request->jenis_tanaman,
            'nama_lahan'    => $request->nama_lahan,
            'alamat_lahan'  => $request->alamat_lahan,
            'status'        => 'aktif',
        ]);

        return response()->json([
            'message' => 'Tanaman berhasil ditambahkan',
            'tanaman' => $tanaman,
        ], 201);
    }

    public function show(Tanaman $tanaman)
    {
        if ($tanaman->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($tanaman->load('perawatan', 'panen'));
    }

    public function update(Request $request, Tanaman $tanaman)
    {
        if ($tanaman->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'jenis_tanaman' => 'sometimes|string',
            'nama_lahan'    => 'nullable|string',
            'alamat_lahan'  => 'nullable|string',
            'status'        => 'sometimes|in:aktif,panen,gagal',
        ]);

        $tanaman->update($request->only(['jenis_tanaman', 'nama_lahan', 'alamat_lahan', 'status']));

        return response()->json([
            'message' => 'Tanaman berhasil diupdate',
            'tanaman' => $tanaman,
        ]);
    }

    public function destroy(Tanaman $tanaman)
    {
        if ($tanaman->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tanaman->delete();

        return response()->json(['message' => 'Tanaman berhasil dihapus']);
    }
}