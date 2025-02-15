<?php

declare(strict_types=1);

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Providers\AppServiceProvider;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('binds interfaces to implementations', function (): void {
    $this->app->register(AppServiceProvider::class);

    $categoryRepository = $this->app->make(CategoryRepositoryInterface::class);
    $productRepository = $this->app->make(ProductRepositoryInterface::class);
    $commentRepository = $this->app->make(CommentRepositoryInterface::class);

    expect($categoryRepository)->toBeInstanceOf(CategoryRepository::class)
        ->and($productRepository)->toBeInstanceOf(ProductRepository::class)
        ->and($commentRepository)->toBeInstanceOf(CommentRepository::class);
});
