<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            // Simpan URL tujuan agar setelah login langsung diarahkan ke sana
            return redirect()->route('login')
                ->with('info', 'Silakan masuk atau buat akun terlebih dahulu untuk melanjutkan pemesanan.')
                ->with('redirect_after_login', $request->fullUrl());
        }

        return $next($request);
    }
}
