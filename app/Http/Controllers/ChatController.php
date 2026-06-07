<?php

namespace App\Http\Controllers;

use App\Models\ChatKonsultasi;
use App\Models\ChatTransaksi;
use App\Models\Konsultasi;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Chat Konsultasi (Petani - Ahli)
    public function konsultasi(Konsultasi $konsultasi)
    {
        $chat = ChatKonsultasi::where('konsultasi_id', $konsultasi->id)
            ->with('pengirim')
            ->latest()
            ->get();

        return response()->json($chat);
    }

    public function sendKonsultasi(Request $request, Konsultasi $konsultasi)
    {
        $request->validate([
            'pesan' => 'required|string',
        ]);

        $chat = ChatKonsultasi::create([
            'konsultasi_id' => $konsultasi->id,
            'pengirim_id'   => Auth::id(),
            'pesan'         => $request->pesan,
            'status'        => 'sent',
        ]);

        return response()->json([
            'message' => 'Pesan terkirim',
            'chat'    => $chat->load('pengirim'),
        ], 201);
    }

    // Chat Transaksi (Petani - Pedagang)
    public function transaksi(Pesanan $pesanan)
    {
        // Cek akses - hanya petani & pedagang terkait
        if ($pesanan->pedagang_id !== Auth::id() && $pesanan->petani_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Chat hanya bisa diakses kalau pesanan sudah validated
        if ($pesanan->status === 'pending' || $pesanan->status === 'rejected') {
            return response()->json(['message' => 'Chat belum tersedia'], 403);
        }

        $chat = ChatTransaksi::where('pesanan_id', $pesanan->id)
            ->with('pengirim')
            ->latest()
            ->get();

        return response()->json($chat);
    }

    public function sendTransaksi(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->pedagang_id !== Auth::id() && $pesanan->petani_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($pesanan->status === 'pending' || $pesanan->status === 'rejected') {
            return response()->json(['message' => 'Chat belum tersedia'], 403);
        }

        $request->validate([
            'pesan' => 'required|string',
        ]);

        $chat = ChatTransaksi::create([
            'pesanan_id'  => $pesanan->id,
            'pengirim_id' => Auth::id(),
            'pesan'       => $request->pesan,
            'status'      => 'sent',
        ]);

        return response()->json([
            'message' => 'Pesan terkirim',
            'chat'    => $chat->load('pengirim'),
        ], 201);
    }
}