<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\User\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('users/search', [AdminUserController::class, 'search']); 
Route::resource('users',AdminUserController::class);

Route::resource('categories',AdminCategoryController::class);
Route::post('categories/search', [AdminCategoryController::class, 'search']);

Route::post('products/search', [ProductController::class, 'search']);
Route::resource('products',ProductController::class);
Route::get('products/categoryProducts',[ProductController::class,'categoryProducts']);




