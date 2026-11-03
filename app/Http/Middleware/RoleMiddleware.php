<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Memeriksa apakah role pengguna yang sedang login diizinkan
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika belum login atau role-nya tidak termasuk yang diizinkan
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}