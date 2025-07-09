<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Rota necessária para Sanctum emitir cookies corretamente
Route::middleware(['web'])->get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Rota genérica para SPA do React (vem por último!)
Route::middleware(['web'])->get('/{any}', function () {
    return file_get_contents(public_path('index.html'));
})->where('any', '.*');