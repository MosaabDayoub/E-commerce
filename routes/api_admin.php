<?php

use App\Http\Controllers\Admin\DeleteAccountController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\AdminController;

//  Authentication Routes (Public)
Route::post('/login', [LoginController::class, '__invoke']);

//  Protected Routes
Route::middleware(['auth:sanctum:admin_api'])->group(function () {

    //  Profile Management 
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);
    });

    //  Password Reset 
    Route::prefix('password')->group(function () {
        Route::post('/reset-code', [ProfileController::class, 'requestResetCode']);
        Route::post('/reset', [ProfileController::class, 'resetPassword']);
    });

    //  Session Management 
    Route::post('/logout', [LogoutController::class, '__invoke']);
    Route::delete('/account', [DeleteAccountController::class, '__invoke']);

    // ============ ADMINS MANAGEMENT ============
    Route::middleware(['permission:view-admins'])->prefix('admins')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/search', [AdminController::class, 'search']);
        Route::get('/{admin}', [AdminController::class, 'show']);

        Route::middleware(['permission:create-admins'])->group(function () {
            Route::post('/', [AdminController::class, 'store']);
        });

        Route::middleware(['permission:edit-admins'])->group(function () {
            Route::put('/{admin}', [AdminController::class, 'update']);
            
            // Admin Roles Management
            Route::prefix('/{admin}')->group(function () {
                Route::post('/assign-role', [AdminController::class, 'assignRole']);
                Route::post('/remove-role', [AdminController::class, 'removeRole']);
                Route::post('/sync-roles', [AdminController::class, 'syncRoles']);
                
                // Admin Permissions Management
                Route::post('/give-permission', [AdminController::class, 'givePermissionTo']);
                Route::post('/revoke-permission', [AdminController::class, 'revokePermissionFrom']);
                Route::post('/sync-permissions', [AdminController::class, 'syncPermissions']);
                Route::get('/direct-permissions', [AdminController::class, 'getDirectPermissions']);
            });
        });

        Route::middleware(['permission:delete-admins'])->group(function () {
            Route::delete('/{admin}', [AdminController::class, 'destroy']);
        });
    });

    // ============ PERMISSIONS & ROLES MANAGEMENT ============
    Route::middleware(['permission:manage-roles'])->prefix('roles')->group(function () {
        // Permissions Management
        Route::get('/permissions/all', [AdminController::class, 'getAllPermissions']);
        
        // Role-Permission Management
        Route::post('/give-permission', [AdminController::class, 'givePermissionToRole']);
        Route::post('/revoke-permission', [AdminController::class, 'revokePermissionFromRole']);
        Route::post('/sync-permissions', [AdminController::class, 'syncRolePermissions']);
        Route::get('/{role}/permissions', [AdminController::class, 'getRolePermissions']);
    });

    //  Products Management
    Route::middleware(['permission:view-products'])->prefix('products')->group(function () {
        // 👁️ View Permissions
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::post('/search', [ProductController::class, 'search']);

        //  Edit Permissions
        Route::middleware(['permission:edit-products'])->group(function () {
            Route::put('/{product}', [ProductController::class, 'update']);
            
            Route::prefix('/{product}')->group(function () {
                // Colors Management
                Route::prefix('/colors')->group(function () {
                    Route::post('/add', [ProductController::class, 'addColorsToProduct']);
                    Route::post('/remove', [ProductController::class, 'removeColorsFromProduct']);
                });
                
                // Images Management
                Route::prefix('/images')->group(function () {
                    Route::delete('/main', [ProductController::class, 'deleteMainImage']);
                    Route::delete('/gallery', [ProductController::class, 'clearGallery']);
                    Route::delete('/gallery/{mediaId}', [ProductController::class, 'deleteGalleryImage']);
                });
                
                // Sizes Management
                Route::prefix('/sizes')->group(function () {
                    Route::post('/add', [ProductController::class, 'addSizesToProduct']);
                    Route::post('/remove', [ProductController::class, 'removeSizesFromProduct']);
                });
            });
        });

        //  Delete Permissions
        Route::middleware(['permission:delete-products'])->group(function () {
            Route::delete('/{product}', [ProductController::class, 'destroy']);
        });

        //  Create Permissions
        Route::middleware(['permission:create-products'])->group(function () {
            Route::post('/', [ProductController::class, 'store']);
        });
    });

    //  Categories Management
    Route::middleware(['permission:view-categories'])->prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::post('/search', [CategoryController::class, 'search']);

        Route::middleware(['permission:create-categories'])->group(function () {
            Route::post('/', [CategoryController::class, 'store']);
        });

        Route::middleware(['permission:edit-categories'])->group(function () {
            Route::put('/{category}', [CategoryController::class, 'update']);
            Route::patch('/{category}', [CategoryController::class, 'update']);
        });

        Route::middleware(['permission:delete-categories'])->group(function () {
            Route::delete('/{category}', [CategoryController::class, 'destroy']);
        });
    });

    //  Colors Management
    Route::middleware(['permission:view-colors'])->prefix('colors')->group(function () {
        Route::get('/', [ColorController::class, 'index']);
        Route::get('/{color}', [ColorController::class, 'show']);

        Route::middleware(['permission:create-colors'])->group(function () {
            Route::post('/', [ColorController::class, 'store']);
        });

        Route::middleware(['permission:edit-colors'])->group(function () {
            Route::put('/{color}', [ColorController::class, 'update']);
        });

        Route::middleware(['permission:delete-colors'])->group(function () {
            Route::delete('/{color}', [ColorController::class, 'destroy']);
        });
    });

    //  Sizes Management
    Route::middleware(['permission:view-sizes'])->prefix('sizes')->group(function () {
        Route::get('/', [SizeController::class, 'index']);
        Route::get('/{size}', [SizeController::class, 'show']);

        Route::middleware(['permission:create-sizes'])->group(function () {
            Route::post('/', [SizeController::class, 'store']);
        });

        Route::middleware(['permission:edit-sizes'])->group(function () {
            Route::put('/{size}', [SizeController::class, 'update']);
        });

        Route::middleware(['permission:delete-sizes'])->group(function () {
            Route::delete('/{size}', [SizeController::class, 'destroy']);
        });
    });

    //  Users Management
    Route::middleware(['permission:view-users'])->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::get('/search', [UserController::class, 'search']);

        Route::middleware(['permission:create-users'])->group(function () {
            Route::post('/', [UserController::class, 'store']);
        });

        Route::middleware(['permission:edit-users'])->group(function () {
            Route::put('/{user}', [UserController::class, 'update']);
            
            // Role Management
            Route::prefix('/{user}')->group(function () {
                Route::post('/assign-role', [UserController::class, 'assignRole']);
                Route::post('/remove-role', [UserController::class, 'removeRole']);
                Route::post('/sync-roles', [UserController::class, 'syncRoles']);
                
                // User Permissions Management
                Route::post('/give-permission', [UserController::class, 'givePermissionTo']);
                Route::post('/revoke-permission', [UserController::class, 'revokePermissionFrom']);
                Route::post('/sync-permissions', [UserController::class, 'syncPermissions']);
                Route::get('/direct-permissions', [UserController::class, 'getDirectPermissions']);
            });
        });

        Route::middleware(['permission:delete-users'])->group(function () {
            Route::delete('/{user}', [UserController::class, 'destroy']);
        });
    });

    //  Cart Management 
    Route::middleware(['permission:view-carts'])->prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/{cart}', [CartController::class, 'show']);
    });

    //  Order Management 
    Route::middleware(['permission:view-orders'])->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
        Route::put('/{order}', [OrderController::class, 'update']);
        Route::delete('/{order}', [OrderController::class, 'destroy']);
    });

});