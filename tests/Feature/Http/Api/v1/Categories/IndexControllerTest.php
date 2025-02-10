<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list only parent categories', function (): void {
    // Arrange: Create a parent category
    $parentCategory = Category::factory()->create()->refresh();

    // Arrange: Create a subcategory
    $subcategory = Category::factory()->create(['parent_id' => $parentCategory->id])->refresh();

    // Act: Send a GET request to the index endpoint
    $response = $this->getJson('/api/v1/categories');

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the parent category
    $response->assertJsonFragment([$parentCategory->slug]);

    // Assert: Check if the response contains the subcategory
    $response->assertJsonFragment([$subcategory->slug]);

    // Assert: Check if the subcategory is a child of the parent category
    $subCategories = $response->json()['data'][0]['subcategories'];
    $this->assertContains($subcategory->slug, array_column($subCategories, 'slug'));
    $this->assertNotContains($parentCategory->slug, array_column($subCategories, 'slug'));
});

it('returns the correct response structure for the list of categories', function (): void {
    // Act: Send a GET request to the index endpoint
    $response = $this->getJson('/api/v1/categories');

    // Assert: Check if the response format is correct
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'slug',
                'subcategories' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'image',
                    ],
                ],
            ],
        ],
    ]);
})->with([
    fn () => Category::factory()->create(),
    fn () => Category::factory()->has(Category::factory()->count(2), 'subcategories')->create(),
]);
