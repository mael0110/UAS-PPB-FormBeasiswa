<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 🌟 KODE UNTUK MEMBAGI REDIRECT BERDASARKAN ROLE 🌟
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->intended('/admin/dashboard'); // sesuaikan rute URL admin kamu
        } elseif ($user->role === 'pegawai') {
            return redirect()->intended('/pegawai/dashboard'); // sesuaikan rute URL pegawai kamu
        }

        // Default jika role-nya mahasiswa atau yang lain
        return redirect()->intended('/mahasiswa/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
