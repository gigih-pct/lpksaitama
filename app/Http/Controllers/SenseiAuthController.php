<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Banner;
use App\Models\Sensei;

class SenseiAuthController extends Controller
{
    public function showLoginForm()
    {
        // Banners for auth might be shared or specific
        $banners = Banner::where('is_active', true)
            ->where('lokasi', 'Auth')
            ->whereIn('role_target', ['Sensei', 'Semua'])
            ->get();

        return view('sensei.auth.login', compact('banners'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('sensei')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/sensei');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        $banners = Banner::where('is_active', true)
            ->where('lokasi', 'Auth')
            ->whereIn('role_target', ['Sensei', 'Semua'])
            ->get();

        return view('sensei.auth.register', compact('banners'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nomor_induk' => ['required', 'string', 'max:255', 'unique:senseis'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:senseis'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $sensei = Sensei::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nomor_induk' => $validated['nomor_induk'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('sensei.login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::guard('sensei')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sensei.login');
    }
}
