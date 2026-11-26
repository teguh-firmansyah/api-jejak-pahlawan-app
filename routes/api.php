<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EraController;
use App\Http\Controllers\Api\HeroNoteController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('eras', EraController::class);
    Route::apiResource('hero-notes', HeroNoteController::class);
});
