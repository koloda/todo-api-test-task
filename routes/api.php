<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index']);
    Route::get('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'show']);
    Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store']);
    Route::put('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'destroy']);
    Route::post('/tasks/{id}/done', [\App\Http\Controllers\TaskController::class, 'done']);
});
