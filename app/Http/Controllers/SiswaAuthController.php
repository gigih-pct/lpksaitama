<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\Banner;

class SiswaAuthController extends Controller
{
    public function showLoginForm()
    {
        $banners = Banner::where('is_active', true)
            ->where('lokasi', 'Auth')
            ->where('role_target', 'Siswa')
            ->get();
        return view('siswa.auth.login', compact('banners'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('siswa')->attempt($credentials, $request->boolean('remember'))) {
            if (!Auth::guard('siswa')->user()->is_approved) {
                Auth::guard('siswa')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum disetujui oleh admin. Silakan tunggu atau hubungi admin.',
                ])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended('/siswa');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        $banners = Banner::where('is_active', true)
            ->where('lokasi', 'Auth')
            ->where('role_target', 'Siswa')
            ->get();
        return view('siswa.auth.register', compact('banners'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nomor_induk' => ['required', 'string', 'max:255', 'unique:siswas'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:siswas'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $siswa = Siswa::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nomor_induk' => $validated['nomor_induk'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status_siswa' => 'Calon',
            'is_approved' => false,
        ]);

        // Jangan auto login, kembalikan ke halaman login dengan pesan sukses
        return redirect()->route('siswa.login')->with('success', 'Registrasi berhasil. Akun Anda sedang menunggu persetujuan Admin.');
    }

    public function logout(Request $request)
    {
        Auth::guard('siswa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/siswa/login');
    }
}
