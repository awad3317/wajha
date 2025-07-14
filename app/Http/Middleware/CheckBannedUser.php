<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('sanctum')->check() && auth('sanctum')->user()->is_banned) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been suspended. Please contact support for more information.',
            ], 403);
        }

        return $next($request);
    }
}
