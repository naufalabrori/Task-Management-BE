<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Task Header API
Route::apiResource('/TaskHeader', App\Http\Controllers\Api\TaskHeaderController::class);

// Task Detail API
Route::apiResource('/TaskDetail', App\Http\Controllers\Api\TaskDetailController::class);
Route::put('TaskDetail/ticked/{id}', [App\Http\Controllers\Api\TaskDetailController::class, 'ticked']);
