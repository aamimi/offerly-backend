<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use App\Queries\Category\Relations\CategoryMetaTagRelation;
use Illuminate\Database\Eloquent\Builder;

it('loads metaTag relation', function (): void {
    // Arrange
    $filter = new CategoryMetaTagRelation();
    $query = Category::query();

    // Act
    $resultQuery = $filter->apply($query);

    // Assert
    expect($resultQuery)->toBeInstanceOf(Builder::class);

    // Get the eager loads from the query
    $eagerLoads = $resultQuery->getEagerLoads();

    // Assert that 'metaTag' relation is being loaded
    expect($eagerLoads)->toHaveKey('metaTag');
});

it('can be instantiated', function (): void {
    expect(new CategoryMetaTagRelation())
        ->toBeInstanceOf(CategoryMetaTagRelation::class);
});

test('implementation matches interface', function (): void {
    expect(CategoryMetaTagRelation::class)
        ->toImplement(QueryFilterInterface::class);
});
