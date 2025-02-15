<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use App\Queries\Category\Relations\CategoryAllSubcategoriesRelation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('loads subcategories relation with specific columns and ordering', function (): void {
    // Arrange
    $filter = new CategoryAllSubcategoriesRelation();
    $query = Category::query();

    // Act
    $resultQuery = $filter->apply($query);

    // Assert
    expect($resultQuery)->toBeInstanceOf(Builder::class);

    // Get the eager loads from the query
    $eagerLoads = $resultQuery->getEagerLoads();

    // Assert that 'subcategories' relation is being loaded
    expect($eagerLoads)->toHaveKey('subcategories');

    // Create a mock category to test the relation closure
    $category = new Category();
    $relation = $category->subcategories();

    expect($relation)->toBeInstanceOf(HasMany::class);

    // Execute the eager loading closure
    $eagerLoads['subcategories']($relation);

    // Get the query being built for the relation
    $relationQuery = $relation->getQuery();

    // Assert selected columns
    expect($relationQuery->getQuery()->columns)->toBe([
        'id',
        'name',
        'slug',
        'parent_id',
        'image_url',
    ]);

    // Assert ordering
    $orders = $relationQuery->getQuery()->orders;
    expect($orders)->toHaveCount(1)
        ->and($orders[0]['column'])->toBe('name')
        ->and($orders[0]['direction'])->toBe('asc');
});

it('can be instantiated', function (): void {
    expect(new CategoryAllSubcategoriesRelation())
        ->toBeInstanceOf(CategoryAllSubcategoriesRelation::class);
});

test('implementation matches interface', function (): void {
    expect(CategoryAllSubcategoriesRelation::class)
        ->toImplement(QueryFilterInterface::class);
});
