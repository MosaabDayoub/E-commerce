<?php

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\DeleteAccountController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\LogoutController;
use App\Http\Controllers\User\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;


// ==================== PUBLIC ROUTES ====================
Route::post('/register', [RegisterController::class, '__invoke']);  
Route::post('/login', [LoginController::class, '__invoke']);        
Route::post('/password/requestResetCode', [ProfileController::class, 'requestResetCode'])->name('password.reset-code');
Route::post('/password/resetPassword', [ProfileController::class, 'resetPassword'])->name('password.reset');

// ==================== PROTECTED ROUTES ====================
Route::middleware(['auth:sanctum', 'auth:api'])->group(function () {

    // ==================== PROFILE MANAGEMENT ====================
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::post('/', [ProfileController::class, 'update']);
        Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);
    });

    // ==================== SESSION MANAGEMENT ====================
    Route::post('/logout', [LogoutController::class, '__invoke']);
    Route::delete('/account', [DeleteAccountController::class, '__invoke']);

    // ==================== CATEGORIES MANAGEMENT ====================
    Route::middleware(['permission:view-categories'])->prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::post('/search', [CategoryController::class, 'search']);
    });

    // ==================== PRODUCTS MANAGEMENT ====================
    Route::middleware(['permission:view-products'])->prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::get('/search', [ProductController::class, 'search']);
    });

    // ==================== CART MANAGEMENT ====================
    Route::middleware(['permission:view-cart'])->prefix('carts')->group(function () {
        Route::get('/{cart}', [CartController::class, 'show']);
        Route::get('/cost', [CartController::class, 'getCartCost']);

        Route::middleware(['permission:edit-carts'])->group(function () {
            Route::post('/', [CartController::class, 'store']);
            Route::put('/cartItems/{cartItem}', [CartController::class, 'updateItem']);
        });

        Route::middleware(['permission:delete-carts'])->group(function () {
            Route::delete('/{cart}', [CartController::class, 'destroy']);
            Route::delete('/cartItems/{cartItem}', [CartController::class, 'removeItem']);
        });
    });

    // ==================== ORDERS MANAGEMENT ====================
    Route::middleware(['permission:view-order'])->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{order}', [OrderController::class, 'show']);

        Route::middleware(['permission:create-orders'])->group(function () {
            Route::post('/', [OrderController::class, 'store']);
            Route::put('/{order}', [OrderController::class, 'update']);
        });

        Route::middleware(['permission:edit-orders'])->group(function () {
            Route::put('/{order}', [OrderController::class, 'update']);
        });

        Route::middleware(['permission:delete-carts'])->group(function () {
            Route::delete('/{order}', [OrderController::class, 'destroy']);
            Route::delete('/orderItem/{orderItem}', [OrderController::class, 'removeItem']);
        });
    });
});