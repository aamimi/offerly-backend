<?php

declare(strict_types=1);

use App\Contracts\Queries\ProductQueryInterface;
use App\Models\Product;
use App\Queries\Product\Filters\CategoryProductsFilter;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->query = Product::query();
});

it('implements ProductQueryInterface', function (): void {
    $filter = CategoryProductsFilter::class;

    expect($filter)->toImplement(ProductQueryInterface::class);
});

it('returns original query when categories ids is null', function (): void {
    $filter = new CategoryProductsFilter(null);

    $result = $filter->apply($this->query);

    // Verify no where clause was added
    expect($result->getQuery()->wheres)->toBeEmpty();
});

it('adds whereIn clause with category ids', function (): void {
    $categoryIds = [1, 2, 3];
    $filter = new CategoryProductsFilter($categoryIds);

    $result = $filter->apply($this->query);

    // Verify the whereIn clause was added correctly
    $whereClause = $result->getQuery()->wheres[0];
    expect($whereClause)
        ->toHaveKey('type', 'In')
        ->toHaveKey('column', 'category_id')
        ->and($whereClause['values'])->toBe($categoryIds);
});

it('handles empty array of category ids', function (): void {
    $filter = new CategoryProductsFilter([]);

    $result = $filter->apply($this->query);

    // Verify the whereIn clause was added with empty array
    $whereClause = $result->getQuery()->wheres[0];
    expect($whereClause)
        ->toHaveKey('type', 'In')
        ->toHaveKey('column', 'category_id')
        ->and($whereClause['values'])->toBe([]);
});

it('returns instance of Builder', function (): void {
    $filter = new CategoryProductsFilter([1, 2, 3]);

    $result = $filter->apply($this->query);

    expect($result)->toBeInstanceOf(Builder::class);
});

it('maintains existing query constraints', function (): void {
    // Add an existing where clause
    $this->query->where('price', '>', 100);

    $filter = new CategoryProductsFilter([1, 2]);
    $result = $filter->apply($this->query);

    // Verify both constraints exist
    $whereClauses = $result->getQuery()->wheres;
    expect($whereClauses)
        ->toHaveCount(2)
        ->and($whereClauses[0]['column'])->toBe('price')
        ->and($whereClauses[1]['column'])->toBe('category_id');
});

it('works with single category id', function (): void {
    $filter = new CategoryProductsFilter([1]);

    $result = $filter->apply($this->query);

    $whereClause = $result->getQuery()->wheres[0];
    expect($whereClause)
        ->toHaveKey('type', 'In')
        ->toHaveKey('column', 'category_id')
        ->and($whereClause['values'])->toBe([1]);
});
