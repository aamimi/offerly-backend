<?php

declare(strict_types=1);

use App\DTOs\Product\IndexFilterDTO;
use App\DTOs\Product\ProductsResponseDTO;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;

it('returns published products based on filter criteria', function (): void {
    // Arrange: Create some products
    Product::factory()->published()->count(5)->create();
    Product::factory()->count(3)->create();

    $filter = new IndexFilterDTO(
        skip: 0,
        limit: 10,
        search: '',
        categoriesIds: []
    );

    // Act: Create the repository and call the method
    $repository = new ProductRepository();
    $result = $repository->getPublishedProducts($filter);

    // Assert: Check if the result is the expected response
    expect($result)->toBeInstanceOf(ProductsResponseDTO::class)
        ->and($result->products)->toHaveCount(5)
        ->and($result->total)->toBe(5);
});

it('returns a published product by slug', function (): void {
    // Arrange: Create a published product
    $product = Product::factory()->published()->create();

    // Act: Create the repository and call the method
    $repository = new ProductRepository();
    $result = $repository->getPublishedProductBySlug($product->slug);

    // Assert: Check if the result is the expected product
    expect($result)->toBeInstanceOf(Product::class)
        ->and($result->slug)->toBe($product->slug);
});

it('returns null if product is not found by slug', function (): void {
    // Act: Create the repository and call the method
    $repository = new ProductRepository();
    $result = $repository->getPublishedProductBySlug('invalid-slug');

    // Assert: Check if the result is null
    expect($result)->toBeNull();
});
