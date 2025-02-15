<?php

declare(strict_types=1);

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use App\Services\Product\ShowProductService;
use Mockery\MockInterface;

it('returns a published product by slug', function (): void {
    // Arrange: Create a mock product and repository
    $product = Product::factory()->published()->create();
    $repository = mock(ProductRepositoryInterface::class, function (MockInterface $mock) use ($product): void {
        $mock->shouldReceive('getPublishedProductBySlug')
            ->once()
            ->with($product->slug)
            ->andReturn($product);
    });

    // Act: Create the service and call the handle method
    $service = new ShowProductService($repository);
    $result = $service->handle($product->slug);

    // Assert: Check if the result is the expected product
    expect($result)->toBeInstanceOf(Product::class)
        ->and($result->slug)->toBe($product->slug);
});

it('returns null if product is not found', function (): void {
    // Arrange: Create a mock repository
    $repository = mock(ProductRepositoryInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('getPublishedProductBySlug')
            ->once()
            ->with('invalid-slug')
            ->andReturnNull();
    });

    // Act: Create the service and call the handle method
    $service = new ShowProductService($repository);
    $result = $service->handle('invalid-slug');

    // Assert: Check if the result is null
    expect($result)->toBeNull();
});
