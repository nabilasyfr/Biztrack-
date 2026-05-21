<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthBiztrack
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('biztrack_user')) {
            return redirect()->route('login')->withErrors(['session' => 'Silakan login terlebih dahulu.']);
        }
        return $next($request);
    }
}
