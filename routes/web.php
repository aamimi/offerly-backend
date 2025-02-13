<?php

declare(strict_types=1);

use App\Http\Controllers\Api\v1\Categories\IndexController as CategoriesIndexController;
use App\Http\Controllers\Api\v1\Categories\ShowController as ShowCategoryController;
use App\Http\Controllers\Api\v1\Comments\IndexController as CommentsIndexController;
use App\Http\Controllers\Api\v1\Products\IndexController as ProductsIndexController;
use App\Http\Controllers\Api\v1\Products\ShowController as ShowProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function (): void {
    Route::prefix('v1')->group(function (): void {
        Route::prefix('/categories')->group(function (): void {
            Route::get('/', CategoriesIndexController::class)->name('api.v1.categories.index');
            Route::get('/{slug}', ShowCategoryController::class)->name('api.v1.categories.show');
        });
        Route::prefix('/products')->group(function (): void {
            Route::get('/', ProductsIndexController::class)->name('api.v1.products.index');
            Route::get('/{slug}', ShowProductController::class)->name('api.v1.products.show');
            Route::get('/{slug}/comments', CommentsIndexController::class)->name('api.v1.products.comments');
        });
    });
});
