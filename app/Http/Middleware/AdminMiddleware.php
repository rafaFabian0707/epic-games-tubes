<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminMiddleware — Proteksi route /admin/*
 *
 * Daftarkan di bootstrap/app.php (Laravel 11+/13) dengan alias 'admin':
 *
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->alias([
 *           'admin' => \App\Http\Middleware\AdminMiddleware::class,
 *       ]);
 *   })
 *
 * Behaviour:
 *  - Jika belum login → redirect ke /admin/login
 *  - Jika login tapi bukan admin → redirect ke /admin/login dengan pesan error
 *  - Jika login + is_admin = true → lanjut
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login sama sekali
        if (! auth()->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses Admin Panel.');
        }

        // Login tapi bukan admin
        if (! auth()->user()->is_admin) {
            return redirect()->route('admin.login')
                ->with('error', 'Akun Anda tidak memiliki hak akses Admin Panel.');
        }

        // Akun dinonaktifkan
        if (! auth()->user()->is_active) {
            return redirect()->route('admin.login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi super admin.');
        }

        return $next($request);
    }
}
