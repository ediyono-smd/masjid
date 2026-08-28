<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserBelongsToMosque
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Super Admin can access all mosques
        if ($user->hasRole('SUPER_ADMIN')) {
            return $next($request);
        }

        if (empty($user->mosque_id)) {
            abort(403, 'Akun Anda belum ditautkan ke masjid manapun.');
        }

        return $next($request);
    }
}
