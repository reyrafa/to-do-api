<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::get('/tasks', [TaskController::class, 'show']);
        Route::put('/tasks/{task}', [TaskController::class, 'update']);
        Route::get('/tasks/{task}', [TaskController::class, 'view']);
        Route::post('logout', [AuthController::class, 'logout']);

    });

});

