<?php

declare(strict_types=1);

use App\Models\Category;
use App\Queries\Category\IndexQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns the correct subcategories for the parent category', function (): void {
    // Arrange: Create a parent category
    $category = Category::factory()->create();

    // Arrange: Create 5 subcategories for the parent category
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

it('limits the number of subcategories to 5', function (int $count): void {
    // Arrange: Create a parent category
    $category = Category::factory()->create();

    // Arrange: Create subcategories for the parent category
    Category::factory()->count($count)->create(['parent_id' => $category->id]);

    // Act: Get the first parent category with its subcategories
    $category = (new IndexQuery())->builder()->first()->toArray();

    // Arrange: Calculate the expected number of subcategories
    $expectedCount = min($count, 5);

    // Assert: Check if the number of subcategories is correct
    $this->assertCount($expectedCount, $category['subcategories']);
})->with([2, 5, 10]);

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

it('returns list of parent categories ordered by views desc then display_order asc', function (): void {
    // Arrange: Create parent categories with different display_order and views
    $categories = Category::factory()->count(5)->create();

    // Act: Get the parent categories
    $dbCategories = (new IndexQuery())->builder()->get();

    // Assert: Check if the parent categories are ordered correctly
    $this->assertEquals($dbCategories->pluck('slug')->toArray(), $categories->sortByDesc('views')->sortBy('display_order')->pluck('slug')->toArray());
})->todo();
