<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasPatient
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Kalau belum login (safety)
        if (!$user) {
            return redirect()->route('login');
        }

        // Kalau belum isi data pasien
        if (!$user->patient) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}
