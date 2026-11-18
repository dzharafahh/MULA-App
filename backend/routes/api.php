<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\TaskController;

Route::apiResource('tasks', TaskController::class);

use App\Http\Controllers\Api\AiBreakdownController;

Route::post('tasks/{task}/breakdown', [AiBreakdownController::class, 'breakdown']);

use App\Http\Controllers\Api\SosDeadlineController;
use App\Http\Controllers\Api\ActivityLogController;

Route::get('sos-deadline', [SosDeadlineController::class, 'index']);
Route::post('logs', [ActivityLogController::class, 'store']);
Route::get('stats', [ActivityLogController::class, 'stats']);