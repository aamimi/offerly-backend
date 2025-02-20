<?php

declare(strict_types=1);

use App\Models\Category;
use App\Queries\Category\Filters\SearchCategoriesFilter;
use Illuminate\Database\Eloquent\Builder;

it('returns query unchanged when searchTerm is null')
    ->expect(fn (): Builder => new SearchCategoriesFilter(null)->apply(Category::query()))
    ->toBeInstanceOf(Builder::class);

it('returns query unchanged when searchTerm is empty string')
    ->expect(fn (): Builder => new SearchCategoriesFilter('')->apply(Category::query()))
    ->toBeInstanceOf(Builder::class);

it('returns query unchanged when searchTerm is zero string')
    ->expect(fn (): Builder => new SearchCategoriesFilter('0')->apply(Category::query()))
    ->toBeInstanceOf(Builder::class);

it('filters categories by searchTerm', function (): void {
    $category = Category::factory()->create(['name' => 'Test Category']);
    Category::factory()->create(['name' => 'Another Category']);

    $filteredCategories = new SearchCategoriesFilter('test')->apply(Category::query())->get();

    expect($filteredCategories)
        ->toHaveCount(1)
        ->and($filteredCategories->first()->name)->toBe($category->name);
});

it('filters categories case insensitively', function (): void {
    $category = Category::factory()->create(['name' => 'Test Category']);
    Category::factory()->create(['name' => 'Another Category']);

    $filteredCategories = new SearchCategoriesFilter('TEST')->apply(Category::query())->get();

    expect($filteredCategories)
        ->toHaveCount(1)
        ->and($filteredCategories->first()->name)->toBe($category->name);
});

it('returns no categories when searchTerm does not match any category', function (): void {
    Category::factory()->create(['name' => 'Test Category']);
    Category::factory()->create(['name' => 'Another Category']);

    $filteredCategories = new SearchCategoriesFilter('nonexistent')->apply(Category::query())->get();

    expect($filteredCategories)->toHaveCount(0);
});
