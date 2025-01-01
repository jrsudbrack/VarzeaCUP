<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CheckTokenInDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pegue o token do cabeçalho da requisição
        $token = $request->bearerToken();

        $hashedtoken = hash('sha256', $token);

        // Consulta direta ao banco de dados
        $result = DB::table('personal_access_tokens')
            ->where('token', $hashedtoken)
            ->where('is_revoked', false)
            ->first();
    
        if ($result) {
            return $next($request);
        }
    
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
