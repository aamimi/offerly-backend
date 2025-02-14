<?php

declare(strict_types=1);

use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns category and its subcategories ids by slug', function (): void {
    // Arrange: Create a category with subcategories
    $category = Category::factory()->create(['slug' => 'electronics']);
    $subcategories = Category::factory()->count(3)->create(['parent_id' => $category->id]);

    // Act: Create the repository and call the method
    $repository = new CategoryRepository();
    $result = $repository->getCategoriesIdsBySlug('electronics');

    // Assert: Check if the result contains the category and its subcategories ids
    expect($result)->toBeArray()
        ->and($result)->toContain($category->id);
    foreach ($subcategories as $subcategory) {
        expect($result)->toContain($subcategory->id);
    }
});

it('returns null if category is not found by slug', function (): void {
    // Act: Create the repository and call the method
    $repository = new CategoryRepository();
    $result = $repository->getCategoriesIdsBySlug('invalid-slug');

    // Assert: Check if the result is null
    expect($result)->toBeNull();
});
