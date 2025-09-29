<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\OrderController;

// user routes
Route::get('users/search', [UserController::class, 'search']); 
Route::resource('users',UserController::class)->only(['index','show','update','store','destroy']);

// category routes
Route::resource('categories',CategoryController::class)->only(['index','show','update','store','destroy']);
Route::post('categories/search', [CategoryController::class, 'search']);

// product routes
Route::post('products/search', [ProductController::class, 'search']);
Route::resource('products',ProductController::class)->only(['index','show','update','store','destroy']);
Route::prefix('products/{product}')->group(function () {
    Route::prefix('colors')->group(function () {
        Route::post('/attach', [ProductController::class, 'attachColors']);
        Route::post('/detach', [ProductController::class, 'detachColors']);
        Route::post('/sync', [ProductController::class, 'syncColors']);
    });
    
    Route::prefix('sizes')->group(function () {
        Route::post('/attach', [ProductController::class, 'attachSizes']);
        Route::post('/detach', [ProductController::class, 'detachSizes']);
        Route::post('/sync', [ProductController::class, 'syncSizes']);
    });
});

// order routes
Route::apiResource('orders', OrderController::class);


// cart routes
Route::apiResource('carts', CartController::class)->only(['index','show']);