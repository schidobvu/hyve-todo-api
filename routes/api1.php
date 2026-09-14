<?php

use App\Http\Controllers\Todo\BulkCompleteTodoController;
use App\Http\Controllers\Todo\CreateTodoController;
use App\Http\Controllers\Todo\DeleteTodoController;
use App\Http\Controllers\Todo\GetTodoController;
use App\Http\Controllers\Todo\ListTodosController;
use App\Http\Controllers\Todo\UpdateTodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::post('auth/login', LoginController::class);

Route::middleware('auth:api')->group(function () {

    Route::prefix('todos')->group(function () {
        Route::get('/', ListTodosController::class);
        Route::post('/', CreateTodoController::class);

        Route::group(['prefix' => '{todo}'], function () {
            Route::get('/', GetTodoController::class);
            Route::put('/', UpdateTodoController::class);
            Route::delete('/', DeleteTodoController::class);
        });

        Route::post('/bulk-complete', BulkCompleteTodoController::class);
    });

});

