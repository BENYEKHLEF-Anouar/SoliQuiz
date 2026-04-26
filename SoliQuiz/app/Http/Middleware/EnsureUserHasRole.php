<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            // Return JSON for API routes, redirect for web routes
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect('login');
        }

        $userRole = $request->user()->type_profil;

        // Admin can access everything
        if ($userRole === 'admin') {
            return $next($request);
        }

        // Check if user has any of the required roles
        if (! in_array($userRole, $roles)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Permission non accordée.'], 403);
            }
            abort(403, 'Permission non accordée.');
        }

        return $next($request);
    }
}
