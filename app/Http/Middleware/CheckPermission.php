<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        // 1. Pastikan User Login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // 2. Admin Boleh Mengakses Segalanya (Bypass)
        if ($user->isAdmin()) {
            return $next($request);
        }

        // 3. Cek Apakah User Punya Permission Tersebut
        // Kita menggunakan fungsi hasPermission() yang sudah dibuat di User.php
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        // 4. Jika Tidak Punya Izin, Tampilkan Error 403
        abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}