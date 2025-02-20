<?php

declare(strict_types=1);

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTOs\SearchResponseDTO;
use App\Services\SearchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

beforeEach(function (): void {
    $this->categoryRepository = Mockery::mock(CategoryRepositoryInterface::class);
    $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);
    $this->searchService = new SearchService(
        $this->categoryRepository,
        $this->productRepository
    );
});

it('returns search results when query parameter is provided', function (): void {
    $request = Request::create('/', 'GET', ['query' => 'test']);

    $expectedCategories = new Collection(['category1', 'category2']);
    $expectedProducts = new Collection(['product1', 'product2']);

    $this->categoryRepository
        ->shouldReceive('getCategoriesByName')
        ->with('test')
        ->once()
        ->andReturn($expectedCategories);

    $this->productRepository
        ->shouldReceive('getPublishedProductsByTitle')
        ->with('test')
        ->once()
        ->andReturn($expectedProducts);

    $result = $this->searchService->handle($request);

    expect($result)
        ->toBeInstanceOf(SearchResponseDTO::class)
        ->and($result->categories)->toBe($expectedCategories)
        ->and($result->products)->toBe($expectedProducts);
});

it('handles null search query', function (): void {
    $request = Request::create('/', 'GET', ['query' => null]);

    $this->categoryRepository
        ->shouldReceive('getCategoriesByName')
        ->with(null)
        ->once()
        ->andReturn(new Collection());

    $this->productRepository
        ->shouldReceive('getPublishedProductsByTitle')
        ->with(null)
        ->once()
        ->andReturn(new Collection());

    $result = $this->searchService->handle($request);

    expect($result)
        ->toBeInstanceOf(SearchResponseDTO::class)
        ->and($result->categories)->toBeEmpty()
        ->and($result->products)->toBeEmpty();
});
