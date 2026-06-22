<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8|confirmed',
            'role'        => 'required|in:petani,ahli,pedagang',
            'no_hp'       => 'nullable|string',
            'alamat'      => 'nullable|string',
            'no_rekening' => 'nullable|string',
            'bidang'      => 'nullable|string',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'no_hp'       => $request->no_hp,
            'alamat'      => $request->alamat,
            'no_rekening' => $request->no_rekening,
            'bidang'      => $request->bidang,
            'status'      => 'aktif',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->role . '.dashboard')
            ->with('success', 'Registrasi berhasil, selamat datang!');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $user = Auth::user();

        // Cek akun diblokir
        if ($user->status === 'suspended') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Akun kamu telah diblokir.']);
        }

        $request->session()->regenerate();

        return redirect()->route($user->role . '.dashboard')
            ->with('success', 'Login berhasil!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Kamu telah logout.');
    }
}
