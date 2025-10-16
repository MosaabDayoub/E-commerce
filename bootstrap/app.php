<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function(){
            Route::middleware(['api'])
                            ->prefix('api-user')
                            ->name('user.')
                            ->group(base_path('routes/api_user.php'));


            Route::middleware('api')
                            ->prefix('api-admin')
                            ->name('admin.')
                            ->group(base_path('routes/api_admin.php'));
        }
    )
    
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->appendToGroup('api', \App\Http\Middleware\SetLocale::class);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
