<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            // Session expired: send her back to the login screen instead of a bare 403.
            return $request->expectsJson()
                ? response()->json(['message' => 'Your session expired. Please log in again.'], 401)
                : redirect()->guest(route('admin.login'));
        }

        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        return $next($request);
    }
}
