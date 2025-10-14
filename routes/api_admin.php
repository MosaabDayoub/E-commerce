<?php

use App\Http\Controllers\Admin\DeleteAccountController;
use App\Http\Controllers\Admin\AdminLogoutController;
use App\Http\Controllers\Admin\AdminProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\ColorController as AdminColorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SizeController as AdminSizeController;

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
        Route::post('/addColorsToProduct', [ProductController::class, 'addColorsToProduct']);
        Route::post('/removeColorsFromProducts', [ProductController::class, 'removeColorsFromProduct']);
    });
    
    Route::prefix('images')->group(function () {
        Route::delete('/main', [ProductController::class, 'deleteMainImage']);
        Route::delete('/gallery', [ProductController::class, 'clearGallery']);
        Route::delete('/gallery/{mediaId}', [ProductController::class, 'deleteGalleryImage']);
    });
    
    Route::prefix('sizes')->group(function () {
        Route::post('/addSizesToProduct', [ProductController::class, 'addSizesToProduct']);
        Route::post('/removeSizesFromProduct', [ProductController::class, 'removeSizesFromProduct']);
    });
});

// order routes
Route::apiResource('orders', OrderController::class);


// cart routes
Route::apiResource('carts',CartController::class)->only(['index','show']);

//color
Route::apiResource('colors',AdminColorController::class)->except(['index','show']);

//size
Route::apiResource('sizes',AdminSizeController::class)->except(['index','show']);

    // profile routes
    Route::post('/logout', [AdminLogoutController::class, '__invoke']);
    Route::delete('/account', [DeleteAccountController::class, '__invoke']);
    Route::get('/profile', [AdminProfileController::class, 'show']);
    Route::put('/profile', [AdminProfileController::class, 'update']);
    Route::post('/profile/change-password', [AdminProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::delete('/profile/avatar', [AdminProfileController::class, 'deleteAvatar']);
    Route::post('/password/reset-code', [AdminProfileController::class, 'requestResetCode'])->name('password.reset-code');
    Route::post('/password/reset', [AdminProfileController::class, 'resetPassword'])->name('password.reset');

