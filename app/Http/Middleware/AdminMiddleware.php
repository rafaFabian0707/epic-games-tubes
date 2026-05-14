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
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->is_admin) {
            abort(403, 'Akses ditolak — halaman ini khusus admin.');
        }

        return $next($request);
    }
}
