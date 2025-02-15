<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Product;
use App\Queries\Product\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->queryBuilder = new ProductQueryBuilder();
});

it('has default select columns', function (): void {
    $query = $this->queryBuilder->build();

    $expectedColumns = ['id', 'slug', 'title', 'summary', 'price', 'discount_price', 'rating', 'created_at'];

    expect($query->getQuery()->columns)
        ->toBeArray()
        ->toEqual($expectedColumns);
});

it('can set custom select columns', function (): void {
    $customColumns = ['id', 'title', 'price'];

    $query = $this->queryBuilder
        ->setSelectColumns($customColumns)
        ->build();

    expect($query->getQuery()->columns)
        ->toBeArray()
        ->toEqual($customColumns);
});

it('returns instance of query builder when setting columns', function (): void {
    $result = $this->queryBuilder->setSelectColumns(['id']);

    expect($result)->toBeInstanceOf(ProductQueryBuilder::class);
});

it('returns instance of query builder when adding filter', function (): void {
    $mockFilter = Mockery::mock(QueryFilterInterface::class);

    $result = $this->queryBuilder->addFilter($mockFilter);

    expect($result)->toBeInstanceOf(ProductQueryBuilder::class);
});

it('applies filters to the query', function (): void {
    // Create a mock filter that adds a where clause
    $mockFilter = Mockery::mock(QueryFilterInterface::class);
    $mockFilter->shouldReceive('apply')
        ->once()
        ->andReturnUsing(fn ($query) => $query->where('price', '>', 100));

    $query = $this->queryBuilder
        ->addFilter($mockFilter)
        ->build();

    // Verify the where clause was added
    $whereClause = $query->getQuery()->wheres[0];
    expect($whereClause['column'])->toBe('price')
        ->and($whereClause['operator'])->toBe('>')
        ->and($whereClause['value'])->toBe(100);
});

it('applies multiple filters in order', function (): void {
    // Create two mock filters
    $mockFilter1 = Mockery::mock(QueryFilterInterface::class);
    $mockFilter1->shouldReceive('apply')
        ->once()
        ->andReturnUsing(fn ($query) => $query->where('price', '>', 100));

    $mockFilter2 = Mockery::mock(QueryFilterInterface::class);
    $mockFilter2->shouldReceive('apply')
        ->once()
        ->andReturnUsing(fn ($query) => $query->where('rating', '>=', 4));

    $query = $this->queryBuilder
        ->addFilter($mockFilter1)
        ->addFilter($mockFilter2)
        ->build();

    // Verify both where clauses were added in order
    $whereClauses = $query->getQuery()->wheres;
    expect($whereClauses)->toHaveCount(2)
        ->and($whereClauses[0]['column'])->toBe('price')
        ->and($whereClauses[1]['column'])->toBe('rating');
});

it('builds query with Product model', function (): void {
    $query = $this->queryBuilder->build();

    expect($query)
        ->toBeInstanceOf(Builder::class)
        ->and($query->getModel())->toBeInstanceOf(Product::class);
});
