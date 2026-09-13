<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin', 'admin/*', 'up')) {
            return $next($request);
        }

        try {
            $setting = Setting::current();
        } catch (\Throwable) {
            return $next($request);
        }

        if ($setting->maintenance && ! $request->user()) {
            return response()->view('pages.maintenance', ['site' => $setting], 503);
        }

        return $next($request);
    }
}
