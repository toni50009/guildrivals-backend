<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function atualizar(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'usuario' => $user
        ]);
    }
public function registrarVitoria()
{
    $user = Auth::user();
    $user->increment('vitorias');
    $user->increment('partidas_jogadas');
    return response()->json(['mensagem' => 'Vitória registrada com sucesso.']);
}

public function registrarDerrota()
{
    $user = Auth::user();
    $user->increment('derrotas');
    $user->increment('partidas_jogadas');
    return response()->json(['mensagem' => 'Derrota registrada com sucesso.']);
}

}
