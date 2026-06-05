<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Banner;

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

    public function logout(Request $request)
    {
        Auth::guard('sensei')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sensei.login');
    }
}
