<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use App\Queries\Category\Filters\ParentCategoriesFilter;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->query = Category::query();
});

it('implements QueryFilterInterface', function (): void {
    $filter = ParentCategoriesFilter::class;

    expect($filter)->toImplement(QueryFilterInterface::class);
});

it('returns instance of Builder', function (): void {
    $filter = new ParentCategoriesFilter();
    $result = $filter->apply($this->query);

    expect($result)->toBeInstanceOf(Builder::class);
});

it('filters only parent categories', function (): void {
    // Arrange
    $parentCategory = Category::factory()->create(['parent_id' => null]);
    Category::factory()->create(['parent_id' => $parentCategory->id]);

    $filter = new ParentCategoriesFilter();

    // Act
    $result = $filter->apply($this->query)->get();

    // Assert
    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->id)->toBe($parentCategory->id);
});

it('maintains existing query constraints', function (): void {
    // Arrange
    $this->query->where('name', '!=', '');
    $filter = new ParentCategoriesFilter();

    // Act
    $result = $filter->apply($this->query);

    // Assert
    $whereClauses = $result->getQuery()->wheres;
    expect($whereClauses)
        ->toHaveCount(2)
        ->and($whereClauses[0]['column'])->toBe('name');
});
