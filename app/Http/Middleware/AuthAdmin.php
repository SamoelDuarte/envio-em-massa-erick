<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    function __construct()
    {
        $this->allowedRoles = ['admin', 'user'];
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('authenticated') || !session('userData') || session('userData')->role != "admin" ) {
            return redirect('/admin/login');
        }

        $freshUser = User::where('id', session('userData')->id)->where('active', 1)->first();

        if (!$freshUser) {
            return redirect('/login');
        }

        if (!in_array($freshUser->role, $this->allowedRoles)) {
            return redirect('/login');
        }

        session([
            'userData' => $freshUser
        ]);

        return $next($request);
    }
}