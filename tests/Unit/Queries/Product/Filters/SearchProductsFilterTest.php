<?php

declare(strict_types=1);

use App\Models\Product;
use App\Queries\Product\Filters\SearchProductsFilter;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->query = Product::query();
});

it('implements ProductQueryInterface', function (): void {
    $filter = SearchProductsFilter::class;

    expect($filter)->toImplement(App\Contracts\Queries\ProductQueryInterface::class);
});

it('returns original query when search term is null', function (): void {
    $filter = new SearchProductsFilter(null);

    $result = $filter->apply($this->query);

    expect($result->getQuery()->wheres)->toBeEmpty();
});

it('adds whereRaw clause with correct search pattern', function (): void {
    $filter = new SearchProductsFilter('test');
    $result = $filter->apply($this->query);

    $query = $result->getQuery();
    $whereClause = $query->wheres[0];
    $whereBindings = $query->bindings['where'];
    expect($whereClause)
        ->toHaveKey('type', 'raw')
        ->toHaveKey('sql', '(LOWER(title) LIKE ? OR LOWER(summary) LIKE ?)')
        ->and($whereBindings)->toBe(['%test%', '%test%']);
});

it('converts search term to lowercase', function (): void {
    $filter = new SearchProductsFilter('TEST');
    $result = $filter->apply($this->query);

    $query = $result->getQuery();
    $whereBindings = $query->bindings['where'];
    expect($whereBindings)->toBe(['%test%', '%test%']);
});

it('handles empty string search term', function (): void {
    $filter = new SearchProductsFilter('');
    $result = $filter->apply($this->query);

    $query = $result->getQuery();
    $whereBindings = $query->bindings['where'];
    expect($whereBindings)->toBeEmpty();
});

it('properly handles special characters', function (): void {
    $filter = new SearchProductsFilter('test%_');
    $result = $filter->apply($this->query);

    $query = $result->getQuery();
    $whereBindings = $query->bindings['where'];
    expect($whereBindings)->toBe(['%test%_%', '%test%_%']);
});

it('handles multi-byte characters correctly', function (): void {
    $filter = new SearchProductsFilter('тест');
    $result = $filter->apply($this->query);

    $query = $result->getQuery();
    $whereBindings = $query->bindings['where'];
    expect($whereBindings)->toBe(['%тест%', '%тест%']);
});

it('maintains existing query constraints', function (): void {
    $this->query->where('price', '>', 100);

    $filter = new SearchProductsFilter('test');
    $result = $filter->apply($this->query);

    $whereClauses = $result->getQuery()->wheres;
    expect($whereClauses)
        ->toHaveCount(2)
        ->and($whereClauses[0]['column'])->toBe('price')
        ->and($whereClauses[1]['type'])->toBe('raw');
});

it('returns instance of Builder', function (): void {
    $filter = new SearchProductsFilter('test');

    $result = $filter->apply($this->query);

    expect($result)->toBeInstanceOf(Builder::class);
});

// Integration test with database
it('finds products by title or summary', function (): void {
    // Setup test data
    Product::factory()->create(['title' => 'Test Product']);
    Product::factory()->create(['summary' => 'Test Description']);
    Product::factory()->create(['title' => 'Other Product']);

    $filter = new SearchProductsFilter('test');
    $result = $filter->apply($this->query);

    expect($result->count())->toBe(2);
})->group('integration');
