<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('superadmin')->check()) {
            return redirect()->route('superadmin.login');
        }

        return $next($request);
    }
}
