<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

Route::post('/register', [AuthController::class, 'register']);  // Registrar usuario
Route::post('/login', [AuthController::class, 'login']);        // Iniciar sesión

Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);          // Obtener usuario autenticado
    Route::post('/logout', [AuthController::class, 'logout']); // Cerrar sesión
    Route::post('/refresh', [AuthController::class, 'refresh']); // Refrescar token

    Route::apiResource('users', UserController::class);
    Route::apiResource('books', BookController::class);
    Route::apiResource('loans', LoanController::class);
});

