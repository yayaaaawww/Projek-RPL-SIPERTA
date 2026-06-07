<?php

namespace App\Http\Controllers;

use App\Models\Panen;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanenController extends Controller
{
    public function index()
    {
        $panen = Panen::whereHas('tanaman', function($q) {
            $q->where('user_id', Auth::id());
        })->with('tanaman')->latest()->get();

        return response()->json($panen);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanaman_id'     => 'required|exists:tanaman,id',
            'nama_komoditas' => 'required|string',
            'jumlah_kg'      => 'required|numeric|min:0.1',
            'harga_per_kg'   => 'required|numeric|min:0',
            'foto'           => 'required|image|max:5120',
            'lokasi_lahan'   => 'nullable|string',
        ]);

        // Validasi tanaman milik petani ini
        $tanaman = Tanaman::where('id', $request->tanaman_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$tanaman) {
            return response()->json(['message' => 'Tanaman tidak ditemukan'], 404);
        }

        $fotoPath = $request->file('foto')->store('panen', 'public');

        $panen = Panen::create([
            'tanaman_id'     => $request->tanaman_id,
            'nama_komoditas' => $request->nama_komoditas,
            'jumlah_kg'      => $request->jumlah_kg,
            'harga_per_kg'   => $request->harga_per_kg,
            'foto'           => $fotoPath,
            'lokasi_lahan'   => $request->lokasi_lahan,
            'status'         => 'available',
        ]);

        return response()->json([
            'message' => 'Panen berhasil ditambahkan ke katalog',
            'panen'   => $panen,
        ], 201);
    }

    public function show(Panen $panen)
    {
        return response()->json($panen->load('tanaman'));
    }

    public function update(Request $request, Panen $panen)
    {
        $request->validate([
            'nama_komoditas' => 'sometimes|string',
            'jumlah_kg'      => 'sometimes|numeric|min:0',
            'harga_per_kg'   => 'sometimes|numeric|min:0',
            'status'         => 'sometimes|in:listed,available,sold_out,archived',
        ]);

        $panen->update($request->only([
            'nama_komoditas', 'jumlah_kg', 'harga_per_kg', 'status'
        ]));

        return response()->json([
            'message' => 'Panen berhasil diupdate',
            'panen'   => $panen,
        ]);
    }

    public function destroy(Panen $panen)
    {
        $panen->delete();
        return response()->json(['message' => 'Panen berhasil dihapus']);
    }

    // Untuk pedagang - lihat katalog
    public function katalog(Request $request)
    {
        $query = Panen::where('status', 'available')->with('tanaman.petani');

        if ($request->search) {
            $query->where('nama_komoditas', 'like', '%' . $request->search . '%');
        }

        if ($request->sort === 'harga_terendah') {
            $query->orderBy('harga_per_kg', 'asc');
        } else {
            $query->latest();
        }

        $panen = $query->paginate(12);

        return response()->json($panen);
    }
}