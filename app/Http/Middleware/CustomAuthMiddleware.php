<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CustomAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return response()->json(['message' => 'Token tidak ditemukan'], 401);
        }

        // Pastikan format Bearer token
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Format token harus Bearer {token}'], 401);
        }

        // Ambil token setelah 'Bearer '
        $token = substr($authHeader, 7);

        if (empty($token)) {
            return response()->json(['message' => 'Token kosong'], 401);
        }

        if ($token !== config('app.api_key')) {
            return response()->json(['message' => 'Token tidak valid'], 401);
        }



        return $next($request);
    }
}
