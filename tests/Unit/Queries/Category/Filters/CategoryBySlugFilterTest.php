<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use App\Queries\Category\Filters\CategoryBySlugFilter;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->query = Category::query();
});

it('implements QueryFilterInterface', function (): void {
    $filter = CategoryBySlugFilter::class;

    expect($filter)->toImplement(QueryFilterInterface::class);
});

it('returns instance of Builder', function (): void {
    $filter = new CategoryBySlugFilter('test-slug');
    $result = $filter->apply($this->query);

    expect($result)->toBeInstanceOf(Builder::class);
});

it('filters categories by slug', function (): void {
    // Arrange
    $expectedCategory = Category::factory()->create(['slug' => 'test-category']);
    Category::factory()->create(['slug' => 'other-category']);

    $filter = new CategoryBySlugFilter('test-category');

    // Act
    $result = $filter->apply($this->query)->get();

    // Assert
    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->id)->toBe($expectedCategory->id);
});

it('returns empty collection for non-existent slug', function (): void {
    // Arrange
    Category::factory()->create(['slug' => 'existing-category']);
    $filter = new CategoryBySlugFilter('non-existent-slug');

    // Act
    $result = $filter->apply($this->query)->get();

    // Assert
    expect($result)->toBeEmpty();
});

it('maintains existing query constraints', function (): void {
    // Arrange
    $this->query->where('parent_id', '!=', null);
    $filter = new CategoryBySlugFilter('test-slug');

    // Act
    $result = $filter->apply($this->query);

    // Assert
    $whereClauses = $result->getQuery()->wheres;
    expect($whereClauses)
        ->toHaveCount(2)
        ->and($whereClauses[0]['column'])->toBe('parent_id');
});
