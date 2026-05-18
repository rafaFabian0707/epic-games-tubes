<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AdminAuthController
 *
 * Menangani login/logout khusus admin melalui halaman terpisah (/admin/login).
 * Berbeda dari AuthController biasa:
 *  - Setelah login berhasil, redirect ke admin.dashboard
 *  - Jika sudah login tapi bukan admin → abort 403
 *  - Setelah login berhasil, validasi is_admin = true
 */
class AdminAuthController extends Controller
{
    /**
     * Tampilkan halaman login admin.
     * GET /admin/login
     */
    public function showLogin()
    {
        // Jika sudah login sebagai admin, langsung ke dashboard
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    /**
     * Proses login admin.
     * POST /admin/login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba authenticate
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Pastikan user yang login memang admin
            if (! Auth::user()->is_admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki hak akses admin.',
                ])->onlyInput('email');
            }

            // Pastikan akun aktif
            if (! Auth::user()->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun ini telah dinonaktifkan.',
                ])->onlyInput('email');
            }

            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang di Admin Panel, ' . Auth::user()->username . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout admin → kembali ke halaman login admin.
     * POST /admin/logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('info', 'Anda telah logout dari Admin Panel.');
    }
}
