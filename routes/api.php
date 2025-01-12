<?php


use App\Http\Controllers\v1\BookController;
use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v2\BookV2Controller;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // Read routes - only require authentication
        Route::get('books/search', [BookController::class, 'search']);
        Route::get('categories/search', [CategoryController::class, 'search']);

        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::get('books', [BookController::class, 'index']);
        Route::get('books/{id}', [BookController::class, 'show']);

        // Write routes - require authentication and admin role
        Route::middleware('role:admin')->group(function () {
            Route::post('users', [UserController::class, 'store']);
            Route::put('users/{id}', [UserController::class, 'update']);
            Route::delete('users/{id}', [UserController::class, 'destroy']);
            Route::post('books', [BookController::class, 'store']);
            Route::put('books/{id}', [BookController::class, 'update']);
            Route::delete('books/{id}', [BookController::class, 'destroy']);
            Route::apiResource('/categories', CategoryController::class);
        });
    });
});



Route::group(['prefix' => 'v2'], function () {

    Route::middleware('auth:sanctum')->group(function () {

        // Read routes - only require authentication
        Route::get('books/search', [BookV2Controller::class, 'search']);

        Route::get('books', [BookV2Controller::class, 'index']);
        Route::get('books/{id}', [BookV2Controller::class, 'show']);

        // Write routes - require authentication and admin role
        Route::middleware('role:admin')->group(function () {
            Route::post('books', [BookController::class, 'store']);
            Route::put('books/{id}', [BookController::class, 'update']);
            Route::delete('books/{id}', [BookController::class, 'destroy']);
        });
    });
});
