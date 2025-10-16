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



// category routes
Route::resource('categories',CategoryController::class)->only(['index','show']);
Route::post('categories/search', [CategoryController::class, 'search']);

// product routes
Route::get('products/search', [ProductController::class, 'search']);
Route::resource('products',ProductController::class)->only(['index','show']); 

// register & login routes
Route::post('/register', [RegisterController::class, '__invoke']);
Route::post('/login', [LoginController::class, '__invoke']);

// guarded routes
Route::middleware('auth:sanctum:user_api')->group(function () {
 
    // cart routes
    Route::resource('carts',CartController::class)->only(['show','store','destroy']);
    Route::delete('cartItems/{cartItem}', [CartController::class, 'removeItem']);
    Route::put('/cartItems/{cartItem}', [CartController::class, 'updateItem']);
    Route::get('cart/cost', [CartController::class, 'getCartCost']);
    
    // order routes
    Route::resource('orders',OrderController::class)->only(['index','show','update','store','destroy']);
    Route::delete('orderItem/{orderItem}',[OrderController::class,'removeItem']);
    
    // profile routes
    Route::post('/logout', [LogoutController::class, '__invoke']);
    Route::delete('/account', [DeleteAccountController::class, '__invoke']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar']);
    Route::post('/password/reset-code', [ProfileController::class, 'requestResetCode'])->name('password.reset-code');
    Route::post('/password/reset', [ProfileController::class, 'resetPassword'])->name('password.reset');
});