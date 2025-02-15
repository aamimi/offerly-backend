<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use App\Queries\Category\Relations\CategorySubcategoriesRelation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('loads subcategories relation with specific columns, ordering and limit', function (): void {
    // Arrange
    $filter = new CategorySubcategoriesRelation();
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

    $orders = $relationQuery->getQuery()->orders;
    expect($orders)->toHaveCount(2)
        ->and($orders[0]['column'])->toBe('views')
        ->and($orders[0]['direction'])->toBe('desc')
        ->and($orders[1]['column'])->toBe('display_order')
        ->and($orders[1]['direction'])->toBe('asc')
        ->and($relationQuery->getQuery()->groupLimit['value'])->toBe(CategorySubcategoriesRelation::LIMIT);
});

it('can be instantiated', function (): void {
    expect(new CategorySubcategoriesRelation())
        ->toBeInstanceOf(CategorySubcategoriesRelation::class);
});

test('implementation matches interface', function (): void {
    expect(CategorySubcategoriesRelation::class)
        ->toImplement(QueryFilterInterface::class);
});

test('limit constant is set to 5', function (): void {
    expect(CategorySubcategoriesRelation::LIMIT)->toBe(5);
});
