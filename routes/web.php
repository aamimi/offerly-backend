<?php

declare(strict_types=1);

use App\Http\Controllers\Api\v1\Categories\IndexController;
use App\Http\Controllers\Api\v1\Categories\ShowController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function (): void {
    Route::prefix('v1')->group(function (): void {
        Route::prefix('/categories')->group(function (): void {
            Route::get('/', IndexController::class)->name('api.v1.categories.index');
            Route::get('/{slug}', ShowController::class)->name('api.v1.categories.show');
        });
    });
});
