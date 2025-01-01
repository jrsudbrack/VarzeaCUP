<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            // Inserir usuário no banco
            DB::table('users')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Usuário registrado com sucesso!'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = DB::table('users')->where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Credenciais inválidas'], 401);
            }

            $token = Str::random(60);

            $hashed = hash('sha256', $token);

            DB::table('personal_access_tokens')->insert([
                'tokenable_type' => 'App\Models\User', 
                'tokenable_id' => $user->id,
                'name' => 'auth_token',
                'token' => hash('sha256', $token),
                'abilities' => json_encode(['*']),
                'created_at' => now(),
            ]);

            return response()->json(['token' => $token], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function user(Request $request)
    {
        $token = $request->bearerToken();

        $tokenData = DB::table('personal_access_tokens')
            ->where('token', hash('sha256', $token))
            ->first();

        if (!$tokenData) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $user = DB::table('users')->where('id', $tokenData->tokenable_id)->first();

        return response()->json(['user' => $user]);
    }
}
