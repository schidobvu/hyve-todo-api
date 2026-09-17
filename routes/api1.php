<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Job\GetJobStatusController;
use App\Http\Controllers\Todo\BulkCompleteTodoController;
use App\Http\Controllers\Todo\CreateTodoController;
use App\Http\Controllers\Todo\DeleteTodoController;
use App\Http\Controllers\Todo\GetTodoController;
use App\Http\Controllers\Todo\ListTodosController;
use App\Http\Controllers\Todo\UpdateTodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::prefix('auth')->middleware('throttle:5,1')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/me', \App\Http\Controllers\Auth\GetProfileController::class);

    Route::prefix('todos')->group(function () {
        Route::get('/', ListTodosController::class);
        Route::post('/', CreateTodoController::class);

        Route::group(['prefix' => '{todo}'], function () {
            Route::get('/', GetTodoController::class)->middleware('can:view,todo');;
            Route::put('/', UpdateTodoController::class)->middleware('can:update,todo');
            Route::delete('/', DeleteTodoController::class)->middleware('can:delete,todo');
        });

        Route::post('/bulk-complete', BulkCompleteTodoController::class);
    });

    Route::get('jobs/{uuid}/status', GetJobStatusController::class);
});

