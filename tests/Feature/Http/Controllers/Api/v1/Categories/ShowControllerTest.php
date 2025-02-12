<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show a category', function (): void {
    // Arrange: Create a category
    $category = Category::factory()->create()->refresh();

    // Act: Send a GET request to the show endpoint
    $response = $this->getJson('/api/v1/categories/'.$category->slug);

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the parent category
    $response->assertJsonFragment([$category->slug]);

    // Assert: Check if the response format is correct
    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'slug',
            'meta_title',
            'meta_description',
            'subcategories' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'image',
                ],
            ],
        ],
    ]);
});

it('returns 404 when category not found', function (): void {
    // Act: Send a GET request to the show endpoint with a non-existent slug
    $response = $this->getJson('/api/v1/categories/non-existent-slug');

    // Assert: Check if the response status is 404
    $response->assertStatus(404);
});

it('can show a category with subcategories', function (): void {
    // Arrange: Create a parent category
    $parentCategory = Category::factory()->create()->refresh();

    // Arrange: Create a subcategory
    $subcategory = Category::factory()->create(['parent_id' => $parentCategory->id]);

    // Act: Send a GET request to the show endpoint
    $response = $this->getJson('/api/v1/categories/'.$parentCategory->slug);

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the parent category
    $response->assertJsonFragment([$parentCategory->slug]);

    // Assert: Check if the subcategory is a child of the parent category
    $subCategories = $response->json()['data']['subcategories'];
    $this->assertContains($subcategory->slug, array_column($subCategories, 'slug'));
});
