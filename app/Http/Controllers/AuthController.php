<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Attributes\WithoutMiddleware;
use App\Models\User;

class AuthController extends Controller
{
    // ✅ Cadastro de novo usuário
    public function cadastrar(Request $request)
    {
       $validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255|unique:users,name',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:6',
], [
    'name.required' => 'O nome é obrigatório.',
    'name.unique' => 'Este nome já está em uso.',
    'email.required' => 'O email é obrigatório.',
    'email.email' => 'Formato de email inválido.',
    'email.unique' => 'Email já cadastrado.',
    'password.required' => 'A senha é obrigatória.',
    'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => bcrypt($request->password),
]);

        return response()->json([
            'mensagem' => 'Usuário cadastrado com sucesso!',
            'usuario' => $user,
        ], 201);
    }

    // ✅ Login do usuário e geração do token
    public function login(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'password' => 'required|string',
    ]);

    $credentials = [
        'name' => $request->name,
        'password' => $request->password,
    ];

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    return response()->json([
        'message' => 'Login realizado com sucesso',
        'user' => Auth::user(),
    ]);
}

    // ✅ Retorna dados do usuário autenticado
        public function me(Request $request)
        {
            $user = $request->user();

            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'partidas_jogadas' => $user->partidas_jogadas,
                'vitorias' => $user->vitorias,
                'derrotas' => $user->derrotas,
            ]);
        }

    // ✅ Logout – Revoga o token atual
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensagem' => 'Logout realizado com sucesso!'
        ]);
    }
}
