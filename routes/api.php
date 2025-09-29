<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\User\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('users/search', [UserController::class, 'search']); 
Route::resource('users',UserController::class);

Route::resource('categories',CategoryController::class);
Route::post('categories/search', [CategoryController::class, 'search']);

Route::post('products/search', [ProductController::class, 'search']);
Route::resource('products',ProductController::class);
Route::get('products/categoryProducts',[ProductController::class,'categoryProducts']);


