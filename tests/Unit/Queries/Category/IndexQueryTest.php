<?php

declare(strict_types=1);

use App\Models\Category;
use App\Queries\Category\IndexQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should return the query builder with the correct columns', function (): void {
    $query = new IndexQuery();
    $builder = $query->builder();
    expect($builder->getQuery()->columns)->toBe(['id', 'name', 'slug']);
});

it('should eager load subcategories with the correct columns', function (): void {
    // Arrange: Create a parent category
    $category = Category::factory()->create();

    // Arrange: Create subcategories for the parent category
    Category::factory()->count(2)->create(['parent_id' => $category->id]);

    // Act: Get the parent category with its subcategories
    $category = (new IndexQuery())->builder()->first();

    // Assert: Check if the subcategories are loaded with the correct columns
    $category->subcategories->each(function (Category $subcategory): void {
        expect($subcategory->getAttributes())->toHaveKeys(['id', 'name', 'slug', 'parent_id', 'image_url']);
    });
});

it('returns the correct subcategories for the parent category', function (): void {
    // Arrange: Create a parent category
    $category = Category::factory()->create();

    // Arrange: Create subcategories for the parent category
    $subcategories = Category::factory()->count(2)->create(['parent_id' => $category->id]);

    // Act: Get the first parent category with its subcategories
    $category = (new IndexQuery())->builder()->first();

    // Assert: Check if the subcategories are correct
    $subcategories = $subcategories->pluck('slug')->toArray();
    $category->subcategories->each(function (Category $subcategory) use ($subcategories): void {
        $this->assertContains($subcategory->slug, $subcategories);
    });
});

it('returns the correct response structure for the parent category with subcategories', function (): void {
    // Arrange: Create a parent category
    $category = Category::factory()->create();

    // Arrange: Create subcategories for the parent category
    Category::factory()->count(2)->create(['parent_id' => $category->id]);

    // Act: Get the first parent category with its subcategories
    $category = (new IndexQuery())->builder()->first()->toArray();

    // Assert: Check if the number of subcategories is correct
    $this->assertCount(2, $category['subcategories']);

    // Assert: Check if the response format is correct
    expect($category)->toHaveKeys(['id', 'name', 'slug', 'subcategories'])
        ->and($category['subcategories'])->each->toHaveKeys(['id', 'name', 'slug', 'image_url']);
});

it('limits the number of subcategories', function (int $count): void {
    // Arrange: Create a parent category
    $category = Category::factory()->create();

    // Arrange: Create subcategories for the parent category
    Category::factory()->count($count)->create(['parent_id' => $category->id]);

    // Act: Get the first parent category with its subcategories
    $category = (new IndexQuery())->builder()->first()->toArray();

    // Arrange: Calculate the expected number of subcategories
    $expectedCount = min($count, IndexQuery::LIMIT);

    // Assert: Check if the number of subcategories is correct
    $this->assertCount($expectedCount, $category['subcategories']);
})->with([2, 5, 10]);

it(
    'returns list of categories ordered by display_order asc then by name asc',
    function (array $data, array $order): void {
        // Arrange: Create categories with different display_order and views
        foreach ($data as $item) {
            $categories[] = Category::factory()->create($item);
        }

        // Act: Get the categories
        $dbCategories = (new IndexQuery())->builder()->get();

        // Assert: Check if the categories are ordered correctly
        foreach ($order as $key => $value) {
            $this->assertEquals($dbCategories[$key]->slug, $categories[$value]->slug);
        }
    }
)->with([
    [
        'data' => [
            ['name' => 'Category A', 'display_order' => 0],
            ['name' => 'Category B', 'display_order' => 0],
            ['name' => 'Category C', 'display_order' => 0],
            ['name' => 'Category D', 'display_order' => 0],
            ['name' => 'Category E', 'display_order' => 0],
        ],
        'order' => [0, 1, 2, 3, 4],
    ],
    [
        'data' => [
            ['name' => 'Category B', 'display_order' => 0], // 0
            ['name' => 'Category A', 'display_order' => 0], // 1
            ['name' => 'Category C', 'display_order' => 0], // 2
            ['name' => 'Category E', 'display_order' => 0], // 3
            ['name' => 'Category D', 'display_order' => 0], // 4
        ],
        'order' => [1, 0, 2, 4, 3],
    ],
    [
        'data' => [
            ['name' => 'Category A', 'display_order' => 1], // 0
            ['name' => 'Category B', 'display_order' => 0], // 1
            ['name' => 'Category C', 'display_order' => 1], // 2
            ['name' => 'Category D', 'display_order' => 0], // 3
            ['name' => 'Category E', 'display_order' => 1], // 4
        ],
        'order' => [1, 3, 0, 2, 4],
    ],
    [
        'data' => [
            ['name' => 'Category A', 'display_order' => 5], // 0
            ['name' => 'Category B', 'display_order' => 3], // 1
            ['name' => 'Category C', 'display_order' => 4], // 2
            ['name' => 'Category D', 'display_order' => 1], // 3
            ['name' => 'Category E', 'display_order' => 0], // 4
        ],
        'order' => [4, 3, 1, 2, 0],
    ],
]);

it('orders subcategories by views desc then display_order desc', function (array $order, array $data): void {
    // Arrange: Create a parent category
    $parentCategory = Category::factory()->create();

    // Arrange: Create subcategories with different display_order and views
    foreach ($data as $item) {
        $subcategories[] = Category::factory()->create(['parent_id' => $parentCategory->id, ...$item]);
    }

    // Act: Get the first parent category with its subcategories
    $dbCategory = (new IndexQuery())->builder()->first();
    $dbSubcategories = $dbCategory->subcategories;

    // Assert: Check if the subcategories are ordered correctly
    foreach ($order as $key => $value) {
        $this->assertEquals($dbSubcategories[$key]->slug, $subcategories[$value]->slug);
    }
})->with([
    'empty views and display order' => [
        'order' => [0, 1, 2, 3, 4],
        'data' => [
            ['display_order' => 0, 'views' => 0],
            ['display_order' => 0, 'views' => 0],
            ['display_order' => 0, 'views' => 0],
            ['display_order' => 0, 'views' => 0],
            ['display_order' => 0, 'views' => 0],
        ],
    ],
    'use only display order' => [
        'order' => [0, 3, 2, 4, 1],
        'data' => [
            ['display_order' => 0, 'views' => 0],
            ['display_order' => 5, 'views' => 0],
            ['display_order' => 2, 'views' => 0],
            ['display_order' => 1, 'views' => 0],
            ['display_order' => 4, 'views' => 0],
        ],
    ],
    'use only views' => [
        'order' => [0, 3, 2, 4, 1],
        'data' => [
            ['display_order' => 0, 'views' => 5],
            ['display_order' => 0, 'views' => 0],
            ['display_order' => 0, 'views' => 3],
            ['display_order' => 0, 'views' => 4],
            ['display_order' => 0, 'views' => 1],
        ],
    ],
    'use display order and views' => [
        'order' => [1, 3, 4, 0, 2],
        'data' => [
            ['display_order' => 2, 'views' => 100],
            ['display_order' => 1, 'views' => 200],
            ['display_order' => 2, 'views' => 50],
            ['display_order' => 3, 'views' => 150],
            ['display_order' => 1, 'views' => 100],
        ],
    ],
]);
