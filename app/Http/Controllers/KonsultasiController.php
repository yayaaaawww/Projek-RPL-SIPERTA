<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'petani') {
            $konsultasi = Konsultasi::where('petani_id', $user->id)
                ->with('ahli')
                ->latest()
                ->get();
        } else {
            // Ahli lihat semua konsultasi yang belum dijawab / miliknya
            $konsultasi = Konsultasi::where(function($q) use ($user) {
                $q->where('ahli_id', $user->id)
                  ->orWhere('status', 'sent');
            })->with('petani')->latest()->get();
        }

        return response()->json($konsultasi);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'            => 'required|string',
            'deskripsi'        => 'required|string|min:10',
            'foto'             => 'required|image|max:5120',
            'kategori_tanaman' => 'nullable|string',
        ]);

        $fotoPath = $request->file('foto')->store('konsultasi', 'public');

        $konsultasi = Konsultasi::create([
            'petani_id'        => Auth::id(),
            'judul'            => $request->judul,
            'deskripsi'        => $request->deskripsi,
            'foto'             => $fotoPath,
            'kategori_tanaman' => $request->kategori_tanaman,
            'status'           => 'sent',
        ]);

        return response()->json([
            'message'     => 'Konsultasi berhasil dikirim',
            'konsultasi'  => $konsultasi,
        ], 201);
    }

    public function show(Konsultasi $konsultasi)
    {
        return response()->json($konsultasi->load(['petani', 'ahli', 'chat']));
    }

    public function jawab(Request $request, Konsultasi $konsultasi)
    {
        $request->validate([
            'jawaban' => 'required|string|min:15',
        ]);

        // Cek kalau udah diklaim ahli lain
        if ($konsultasi->ahli_id && $konsultasi->ahli_id !== Auth::id()) {
            return response()->json(['message' => 'Konsultasi sedang ditangani ahli lain'], 422);
        }

        $konsultasi->update([
            'ahli_id' => Auth::id(),
            'jawaban' => $request->jawaban,
            'status'  => 'answered',
        ]);

        return response()->json([
            'message'    => 'Jawaban berhasil dikirim',
            'konsultasi' => $konsultasi,
        ]);
    }

    public function update(Request $request, Konsultasi $konsultasi)
    {
        $konsultasi->update(['status' => 'closed']);
        return response()->json(['message' => 'Konsultasi ditutup']);
    }

    public function destroy(Konsultasi $konsultasi)
    {
        $konsultasi->delete();
        return response()->json(['message' => 'Konsultasi berhasil dihapus']);
    }
}