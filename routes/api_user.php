<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;



Route::resource('users',UserController::class);

Route::resource('categories',CategoryController::class)->only(['index','show']);
Route::post('categories/search', [CategoryController::class, 'search']);

Route::get('products/search', [ProductController::class, 'search']);
Route::resource('products',ProductController::class)->only(['index','show','store']);


Route::resource('carts',CartController::class)->only(['show','store','destroy']);
Route::delete('cartItems/{cartItem}', [CartController::class, 'removeItem']);
Route::put('/cartItems/{cartItem}', [CartController::class, 'updateItem']);
Route::get('cart/cost', [CartController::class, 'getCartCost']);




Route::resource('orders',OrderController::class)->only(['index','show','update','store','destroy']);
Route::delete('orderItem/{orderItem}',[OrderController::class,'removeItem']);

