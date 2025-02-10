<?php

declare(strict_types=1);

use App\Models\Category;
use App\Queries\Category\ShowQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should return the query builder', function (): void {
    $query = new ShowQuery();
    $builder = $query->builder('category-slug');
    expect($builder)->toBeInstanceOf(Builder::class);
});

it('should return the query builder with the correct columns', function (): void {
    $query = new ShowQuery();
    $builder = $query->builder('category-slug');
    expect($builder->getQuery()->columns)->toBe(['id', 'name', 'slug', 'meta_title', 'meta_description']);
});

it('should eager load subcategories with the correct columns', function (): void {
    // Arrange: Create a parent category
    $category = Category::factory()->create()->refresh();

    // Arrange: Create subcategories for the parent category
    Category::factory()->count(2)->create(['parent_id' => $category->id]);

    // Act: Get the parent category with its subcategories
    $category = (new ShowQuery())->builder($category->slug)->first();

    // Assert: Check if the subcategories are loaded with the correct columns
    $category->subcategories->each(function (Category $subcategory): void {
        expect($subcategory->getAttributes())->toHaveKeys(['id', 'name', 'slug', 'parent_id', 'image_url']);
    });
});

it('returns the correct category and the correct subcategories', function (): void {
    // Arrange: Create a category
    $category = Category::factory()->create()->refresh();

    // Arrange: Create subcategories for the parent category
    $subcategories = Category::factory()->count(2)->create(['parent_id' => $category->id]);

    // Act: Get the category
    $category = (new ShowQuery())->builder($category->slug)->first();

    // Assert: Check if the category is correct
    expect($category->slug)->toBe($category->slug);

    // Assert: Check if the subcategories are correct
    $subcategories = $subcategories->pluck('slug')->toArray();
    $category->subcategories->each(function (Category $subcategory) use ($subcategories): void {
        $this->assertContains($subcategory->slug, $subcategories);
    });
});
