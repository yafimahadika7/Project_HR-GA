<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Role yang diizinkan (contoh: 'admin' atau 'super_admin')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Pastikan user terautentikasi dan memiliki role yang sesuai
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Akses ditolak: role tidak sesuai.');
        }

        return $next($request);
    }
}