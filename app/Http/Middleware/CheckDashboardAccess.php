<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDashboardAccess
{
    /**
     * Allow only dashboard users.
     *
     * Allowed roles:
     * - super_admin
     * - admin
     * - contributor
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, ['super_admin', 'admin', 'contributor'], true)) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'You do not have access to the admin console.');
        }

        if (isset($user->status) && $user->status !== 'active') {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Your account is not active.');
        }

        return $next($request);
    }
}