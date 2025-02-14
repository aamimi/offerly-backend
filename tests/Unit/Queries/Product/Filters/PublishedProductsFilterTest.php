<?php

declare(strict_types=1);

use App\Models\Product;
use App\Queries\Product\Filters\PublishedProductsFilter;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->query = Product::query();
});

it('implements ProductQueryInterface', function (): void {
    $filter = PublishedProductsFilter::class;

    expect($filter)->toImplement(App\Contracts\Queries\ProductQueryInterface::class);
});

it('adds whereNotNull clause for published_at', function (): void {
    $filter = new PublishedProductsFilter();
    $result = $filter->apply($this->query);

    $whereClause = $result->getQuery()->wheres[0];
    expect($whereClause)
        ->toHaveKey('type', 'NotNull')
        ->toHaveKey('column', 'published_at');
});

it('maintains existing query constraints', function (): void {
    $this->query->where('price', '>', 100);

    $filter = new PublishedProductsFilter();
    $result = $filter->apply($this->query);

    $whereClauses = $result->getQuery()->wheres;
    expect($whereClauses)
        ->toHaveCount(2)
        ->and($whereClauses[0]['column'])->toBe('price')
        ->and($whereClauses[1]['column'])->toBe('published_at');
});

it('returns instance of Builder', function (): void {
    $filter = new PublishedProductsFilter();

    $result = $filter->apply($this->query);

    expect($result)->toBeInstanceOf(Builder::class);
});

// Integration test with database
it('only returns published products', function (): void {
    // Setup test data
    Product::factory()->create(['published_at' => now()]);
    Product::factory()->create(['published_at' => null]);
    Product::factory()->create(['published_at' => now()->subDay()]);

    $filter = new PublishedProductsFilter();
    $result = $filter->apply($this->query);

    expect($result->count())->toBe(2);
})->group('integration');

// Integration test to verify the exact products returned
it('excludes unpublished products', function (): void {
    // Create products
    $publishedProduct1 = Product::factory()->create(['published_at' => now()]);
    $unpublishedProduct = Product::factory()->create(['published_at' => null]);
    $publishedProduct2 = Product::factory()->create(['published_at' => now()->subDay()]);

    $filter = new PublishedProductsFilter();
    $result = $filter->apply($this->query);

    expect($result->pluck('id')->all())
        ->toBe([$publishedProduct2->id, $publishedProduct1->id])
        ->not->toContain($unpublishedProduct->id);
})->group('integration');
