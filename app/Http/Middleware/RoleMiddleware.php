<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        if ($user->role !== $role) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized role'
            ], 403);
        }

        return $next($request);
    }
}