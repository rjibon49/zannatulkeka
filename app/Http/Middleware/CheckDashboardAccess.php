<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDashboardAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // যদি লগইন করা ইউজার এই ৩টি রোলের কোনোটি না হয়, তবে লগআউট করে বের করে দেবে
        if (auth()->check() && !in_array(auth()->user()->role, ['super_admin', 'admin', 'contributor'])) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'You do not have access to the admin console.');
        }

        return $next($request);
    }
}