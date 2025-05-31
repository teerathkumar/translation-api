<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // Optional
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('translations/search', [TranslationController::class, 'search']);
    Route::get('translations/export/{locale}', [TranslationController::class, 'export']);
    Route::apiResource('translations', TranslationController::class);
});

// Route::get('translations/search', [TranslationController::class, 'search']);
// Route::get('translations/export/{locale}', [TranslationController::class, 'export']);
// Route::apiResource('translations', TranslationController::class);
