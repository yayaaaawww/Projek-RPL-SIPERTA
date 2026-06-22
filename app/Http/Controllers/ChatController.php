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
    // ===== Room Chat Konsultasi (versi web) =====
    public function roomKonsultasi(Konsultasi $konsultasi)
    {
        $user = Auth::user();

        // Hanya petani pemilik atau ahli yang menangani
        if ($konsultasi->petani_id !== $user->id && $konsultasi->ahli_id !== $user->id) {
            abort(403, 'Kamu tidak punya akses ke chat ini.');
        }

        // Chat baru tersedia setelah konsultasi dijawab
        if ($konsultasi->status === 'sent') {
            return redirect()->back()->with('error', 'Chat tersedia setelah ahli menjawab konsultasi.');
        }

        $chat = ChatKonsultasi::where('konsultasi_id', $konsultasi->id)
            ->with('pengirim')
            ->orderBy('created_at')
            ->get();

        $konsultasi->load(['petani', 'ahli']);

        return view('chat.konsultasi', [
            'konsultasi' => $konsultasi,
            'chat'       => $chat,
        ]);
    }

    public function sendKonsultasiChat(Request $request, Konsultasi $konsultasi)
    {
        $user = Auth::user();

        if ($konsultasi->petani_id !== $user->id && $konsultasi->ahli_id !== $user->id) {
            abort(403);
        }

        if ($konsultasi->status === 'sent') {
            abort(403, 'Chat belum tersedia.');
        }

        $request->validate([
            'pesan' => 'required|string',
        ]);

        ChatKonsultasi::create([
            'konsultasi_id' => $konsultasi->id,
            'pengirim_id'   => $user->id,
            'pesan'         => $request->pesan,
            'status'        => 'sent',
        ]);

        return redirect()->route('konsultasi.chat', $konsultasi->id);
    }

    // ===== Room Chat Transaksi (petani <-> pedagang, setelah di-ACC) =====
    public function roomTransaksi(Pesanan $pesanan)
    {
        $user = Auth::user();

        if ($pesanan->petani_id !== $user->id && $pesanan->pedagang_id !== $user->id) {
            abort(403, 'Kamu tidak punya akses ke chat ini.');
        }

        // Chat tersedia setelah pembayaran dikonfirmasi petani
        if (! in_array($pesanan->status, ['validated', 'completed'])) {
            return redirect()->back()->with('error', 'Chat tersedia setelah pesanan dikonfirmasi petani.');
        }

        $chat = ChatTransaksi::where('pesanan_id', $pesanan->id)
            ->with('pengirim')
            ->orderBy('created_at')
            ->get();

        $pesanan->load(['petani', 'pedagang', 'panen']);

        return view('chat.transaksi', [
            'pesanan' => $pesanan,
            'chat'    => $chat,
        ]);
    }

    public function sendTransaksiChat(Request $request, Pesanan $pesanan)
    {
        $user = Auth::user();

        if ($pesanan->petani_id !== $user->id && $pesanan->pedagang_id !== $user->id) {
            abort(403);
        }
        if (! in_array($pesanan->status, ['validated', 'completed'])) {
            abort(403, 'Chat belum tersedia.');
        }

        $request->validate(['pesan' => 'required|string']);

        ChatTransaksi::create([
            'pesanan_id'  => $pesanan->id,
            'pengirim_id' => $user->id,
            'pesan'       => $request->pesan,
            'status'      => 'sent',
        ]);

        return redirect()->route('transaksi.chat', $pesanan->id);
    }

    // ===== (API lama di bawah, tidak dipakai versi Blade) =====
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