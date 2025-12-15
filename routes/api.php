<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HistoryController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/user/foto-profile', [AuthController::class, 'updateFotoProfile']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);

    // notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/{id}', [NotificationController::class, 'show']);

    // History harus di atas!!
    Route::get('/tasks/history', [HistoryController::class, 'history']);
    Route::get('/tasks/history/{id}', [HistoryController::class, 'historyDetail']);

    //task
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});
