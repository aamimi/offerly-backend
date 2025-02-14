<?php

declare(strict_types=1);

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTOs\Product\IndexFilterDTO;
use App\DTOs\Product\ProductsResponseDTO;
use App\Services\Product\IndexProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Mockery\MockInterface;

it('returns published products based on filter criteria', function (): void {
    // Arrange: Create a mock request, repositories, and response
    $request = Request::create('/products', 'GET', [
        'category' => 'electronics',
        'search' => 'laptop',
        'skip' => '0',
        'limit' => '10',
    ]);

    $categoriesIds = [1, 2, 3];
    $productsResponse = new ProductsResponseDTO(
        products: new Collection([
            ['id' => 1, 'title' => 'Laptop', 'price' => 1000],
            ['id' => 2, 'title' => 'Smartphone', 'price' => 500],
        ]),
        total: 2
    );

    $categoryRepository = mock(CategoryRepositoryInterface::class, function (MockInterface $mock) use ($categoriesIds): void {
        $mock->shouldReceive('getCategoriesIdsBySlug')
            ->once()
            ->with('electronics')
            ->andReturn($categoriesIds);
    });

    $productRepository = mock(ProductRepositoryInterface::class, function (MockInterface $mock) use ($productsResponse): void {
        $mock->shouldReceive('getPublishedProducts')
            ->once()
            ->with(Mockery::type(IndexFilterDTO::class))
            ->andReturn($productsResponse);
    });

    // Act: Create the service and call the handle method
    $service = new IndexProductService($productRepository, $categoryRepository);
    $result = $service->handle($request);

    // Assert: Check if the result is the expected response
    expect($result)->toBeInstanceOf(ProductsResponseDTO::class);
    // Add more assertions as needed to verify the contents of $result
});

it('returns published products without category filter', function (): void {
    // Arrange: Create a mock request, repositories, and response
    $request = Request::create('/products', 'GET', [
        'search' => 'laptop',
        'skip' => '0',
        'limit' => '10',
    ]);

    $productsResponse = new ProductsResponseDTO(
        products: new Collection([
            ['id' => 1, 'title' => 'Laptop', 'price' => 1000],
            ['id' => 2, 'title' => 'Smartphone', 'price' => 500],
        ]),
        total: 2
    );

    $categoryRepository = mock(CategoryRepositoryInterface::class);

    $productRepository = mock(ProductRepositoryInterface::class, function (MockInterface $mock) use ($productsResponse): void {
        $mock->shouldReceive('getPublishedProducts')
            ->once()
            ->with(Mockery::type(IndexFilterDTO::class))
            ->andReturn($productsResponse);
    });

    // Act: Create the service and call the handle method
    $service = new IndexProductService($productRepository, $categoryRepository);
    $result = $service->handle($request);

    // Assert: Check if the result is the expected response
    expect($result)->toBeInstanceOf(ProductsResponseDTO::class);
    // Add more assertions as needed to verify the contents of $result
});
