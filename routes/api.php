<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

/*
|--------------------------------------------------------------------------
| Rotas públicas da API
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/cadastrar', [AuthController::class, 'cadastrar']);

Route::get('/battle/teste', function () {
    return response()->json(['message' => 'Sistema de batalha está funcionando!']);
});

Route::get('/mensagem', function () {
    return response()->json(['mensagem' => 'API Laravel conectada com sucesso!']);
});

/*
|--------------------------------------------------------------------------
| Rotas protegidas da API (requer token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/usuario', [AuthController::class, 'me']);
    Route::put('/usuario/atualizar', [UsuarioController::class, 'atualizar']);
    Route::post('/usuario/vitoria', [UsuarioController::class, 'registrarVitoria']);
    Route::post('/usuario/derrota', [UsuarioController::class, 'registrarDerrota']);
    Route::get('/ranking', function () {
    return \App\Models\User::select('name', 'vitorias')
        ->orderByDesc('vitorias')
        ->take(100)
        ->get();
});
    
});