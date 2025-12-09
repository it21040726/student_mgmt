<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
