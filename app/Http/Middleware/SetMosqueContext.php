<?php

namespace App\Http\Middleware;

use App\Models\Mosque;
use App\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetMosqueContext
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function handle(Request $request, Closure $next): Response
    {
        // 1. If route has {slug} or {mosque}
        $slug = $request->route('slug');
        if ($slug) {
            $mosque = Mosque::where('slug', $slug)->first();
            if ($mosque) {
                $this->tenantManager->setMosqueId($mosque->id);
                view()->share('currentMosque', $mosque);
                return $next($request);
            }
        }

        // 2. If user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->mosque_id) {
                $this->tenantManager->setMosqueId($user->mosque_id);
                $mosque = Mosque::find($user->mosque_id);
                view()->share('currentMosque', $mosque);
            } elseif ($user->hasRole('SUPER_ADMIN') && session('active_mosque_id')) {
                $this->tenantManager->setMosqueId(session('active_mosque_id'));
                $mosque = Mosque::find(session('active_mosque_id'));
                view()->share('currentMosque', $mosque);
            }
        }

        return $next($request);
    }
}
