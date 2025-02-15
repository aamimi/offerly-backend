<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Product;
use App\Queries\Product\Filters\SlugProductsFilter;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->query = Product::query();
});

it('implements QueryFilterInterface', function (): void {
    $filter = SlugProductsFilter::class;

    expect($filter)->toImplement(QueryFilterInterface::class);
});

it('returns instance of Builder', function (): void {
    $filter = new SlugProductsFilter('test-slug');
    $result = $filter->apply($this->query);

    expect($result)->toBeInstanceOf(Builder::class);
});

it('filters products by slug', function (): void {
    // Arrange
    $expectedProduct = Product::factory()->create(['slug' => 'test-product']);
    Product::factory()->create(['slug' => 'other-product']);

    $filter = new SlugProductsFilter('test-product');

    // Act
    $result = $filter->apply($this->query)->get();

    // Assert
    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->id)->toBe($expectedProduct->id);
});

it('returns empty collection for non-existent slug', function (): void {
    // Arrange
    Product::factory()->create(['slug' => 'existing-product']);
    $filter = new SlugProductsFilter('non-existent-slug');

    // Act
    $result = $filter->apply($this->query)->get();

    // Assert
    expect($result)->toBeEmpty();
});

it('maintains existing query constraints', function (): void {
    // Arrange
    $this->query->where('published_at', '!=', null);
    $filter = new SlugProductsFilter('test-slug');

    // Act
    $result = $filter->apply($this->query);

    // Assert
    $whereClauses = $result->getQuery()->wheres;
    expect($whereClauses)
        ->toHaveCount(2)
        ->and($whereClauses[0]['column'])->toBe('published_at');
});
