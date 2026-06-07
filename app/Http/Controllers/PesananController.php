<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Panen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('pedagang_id', Auth::id())
            ->with(['panen', 'petani'])
            ->latest()
            ->get();

        return response()->json($pesanan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'panen_id'       => 'required|exists:panen,id',
            'jumlah_beli'    => 'required|numeric|min:0.1',
            'bukti_transfer' => 'required|image|max:5120',
            'catatan'        => 'nullable|string',
        ]);

        $panen = Panen::findOrFail($request->panen_id);

        // Validasi stok
        if ($panen->status !== 'available') {
            return response()->json(['message' => 'Produk tidak tersedia'], 422);
        }

        if ($request->jumlah_beli > $panen->jumlah_kg) {
            return response()->json(['message' => 'Maaf, stok tidak mencukupi'], 422);
        }

        $buktiPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $pesanan = Pesanan::create([
            'panen_id'       => $panen->id,
            'pedagang_id'    => Auth::id(),
            'petani_id'      => $panen->tanaman->user_id,
            'jumlah_beli'    => $request->jumlah_beli,
            'total_harga'    => $request->jumlah_beli * $panen->harga_per_kg,
            'bukti_transfer' => $buktiPath,
            'catatan'        => $request->catatan,
            'status'         => 'pending',
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil dikirim, menunggu konfirmasi petani',
            'pesanan' => $pesanan,
        ], 201);
    }

    public function show(Pesanan $pesanan)
    {
        if ($pesanan->pedagang_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($pesanan->load(['panen', 'petani', 'chatTransaksi']));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        // Petani konfirmasi pembayaran
        if ($pesanan->petani_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:validated,rejected',
            'alasan_tolak' => 'nullable|string',
        ]);

        if ($request->status === 'validated') {
            $panen = $pesanan->panen;
            $sisaStok = $panen->jumlah_kg - $pesanan->jumlah_beli;

            $panen->update([
                'jumlah_kg' => $sisaStok,
                'status'    => $sisaStok <= 0 ? 'sold_out' : 'available',
            ]);

            $pesanan->update(['status' => 'validated']);

            return response()->json([
                'message' => 'Pembayaran dikonfirmasi, stok diperbarui',
                'pesanan' => $pesanan,
            ]);
        }

        $pesanan->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Pesanan ditolak',
            'pesanan' => $pesanan,
        ]);
    }

    public function destroy(Pesanan $pesanan)
    {
        if ($pesanan->pedagang_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pesanan->delete();
        return response()->json(['message' => 'Pesanan berhasil dihapus']);
    }
}