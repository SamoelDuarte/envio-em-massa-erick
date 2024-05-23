<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuth
{
    public function handle($request, Closure $next)
    {
        if (session('authenticated')) {
            return $next($request);
        }

        return redirect('/admin/login');
    }
}
