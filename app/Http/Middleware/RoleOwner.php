<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleOwner
{
    public function handle(Request $request, Closure $next)
    {
        if (session('biztrack_role') !== 'owner') {
            abort(403, 'Akses ditolak. Hanya Owner yang dapat mengakses halaman ini.');
        }
        return $next($request);
    }
}
