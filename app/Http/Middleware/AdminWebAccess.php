<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AdminWebAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Get token from localStorage-sent header
        $token = $request->bearerToken();

        if (!$token) {
            return redirect()->route('auth.login');
        }

        // Check if token exists
        $tokenModel = PersonalAccessToken::findToken($token);

        if (!$tokenModel || !$tokenModel->tokenable) {
            return redirect()->route('auth.login');
        }

        // Check role
        if ($tokenModel->tokenable->role !== 'admin') {
            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}
