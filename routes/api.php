<?php

use App\Http\Controllers\TasksController;
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

Route::get('/tasks', [TasksController::class, 'index']);
Route::prefix('task')->group(function () {
    Route::get('/{task}', [TasksController::class, 'show']);
    Route::post('/store', [TasksController::class, 'store']);
    Route::put('/{task}', [TasksController::class, 'update']);
    Route::delete('/{task}', [TasksController::class, 'destroy']);
});