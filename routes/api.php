<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

Route::prefix('v1')->group(function () {
    // Rutas de autenticación
    Route::post('/register', [AuthController::class, 'register']);  // Registrar usuario
    Route::post('/login', [AuthController::class, 'login']);        // Iniciar sesión

    // Rutas públicas (ahora todas son accesibles sin autenticación)
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::apiResource('users', UserController::class);
Route::apiResource('books', BookController::class);
Route::apiResource('loans', LoanController::class);
