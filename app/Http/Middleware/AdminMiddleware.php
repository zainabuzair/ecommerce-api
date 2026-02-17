<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated AND is an admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // If not, kick them out with a 403 Forbidden
        return response()->json([
            'message' => 'Access Denied. Admins only.'
        ], 403);
    }
}
