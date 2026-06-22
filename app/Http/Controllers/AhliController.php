<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AhliController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $keluhanBelumTerjawab = Konsultasi::where('status', 'sent')->count();

        $keluhanTerjawab = Konsultasi::where('ahli_id', $user->id)
            ->where('status', 'answered')
            ->count();

        $aktivitasTerbaru = Konsultasi::where('ahli_id', $user->id)
            ->with('petani')
            ->latest()
            ->take(5)
            ->get();

        return view('ahli.dashboard', [
            'user'                   => $user,
            'keluhan_belum_terjawab' => $keluhanBelumTerjawab,
            'keluhan_terjawab'       => $keluhanTerjawab,
            'aktivitas_terbaru'      => $aktivitasTerbaru,
        ]);
    }

    // ===== Konsultasi (FR-06 Saran Pakar) =====
    public function konsultasi()
    {
        $user = Auth::user();

        // Keluhan yang belum dijawab siapa pun + keluhan yang sudah ditangani ahli ini
        $konsultasi = Konsultasi::where(function ($q) use ($user) {
                $q->where('status', 'sent')
                  ->orWhere('ahli_id', $user->id);
            })
            ->with('petani')
            ->latest()
            ->get();

        return view('ahli.konsultasi', ['konsultasi' => $konsultasi]);
    }

    public function jawabKonsultasi(Request $request, Konsultasi $konsultasi)
    {
        $request->validate([
            'jawaban' => 'required|string|min:15',
        ]);

        // Kunci: kalau sudah diklaim ahli lain, tolak
        if ($konsultasi->ahli_id && $konsultasi->ahli_id !== Auth::id()) {
            return back()->withErrors(['jawaban' => 'Konsultasi ini sedang ditangani ahli lain.']);
        }

        $konsultasi->update([
            'ahli_id' => Auth::id(),
            'jawaban' => $request->jawaban,
            'status'  => 'answered',
        ]);

        return redirect()->route('ahli.konsultasi')->with('success', 'Jawaban berhasil dikirim ke petani.');
    }

    // ===== Laporan (FR-03) =====
    public function laporan()
    {
        $laporan = Laporan::where('pelapor_id', Auth::id())
            ->with('terlapor')
            ->latest()
            ->get();

        $users = User::where('id', '!=', Auth::id())
            ->where('role', '!=', 'admin')
            ->get();

        return view('ahli.laporan', [
            'laporan' => $laporan,
            'users'   => $users,
        ]);
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'terlapor_id' => 'required|exists:users,id',
            'alasan'      => 'required|string',
            'bukti'       => 'nullable|image|max:5120',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('laporan', 'public');
        }

        Laporan::create([
            'pelapor_id'    => Auth::id(),
            'terlapor_id'   => $request->terlapor_id,
            'jenis_pelapor' => Auth::user()->role,
            'alasan'        => $request->alasan,
            'bukti'         => $buktiPath,
            'status'        => 'pending',
        ]);

        return redirect()->route('ahli.laporan')->with('success', 'Laporan berhasil dikirim ke admin.');
    }

    // ===== Profile =====
    public function profile()
    {
        return view('ahli.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'no_hp'    => 'nullable|string',
            'alamat'   => 'nullable|string',
            'bidang'   => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name   = $request->name;
        $user->email  = $request->email;
        $user->no_hp  = $request->no_hp;
        $user->alamat = $request->alamat;
        $user->bidang = $request->bidang;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('ahli.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
