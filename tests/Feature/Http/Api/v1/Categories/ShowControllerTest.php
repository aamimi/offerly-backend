<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show a category', function (): void {
    // Arrange: Create a category
    $category = Category::factory()->create();

    // Act: Send a GET request to the show endpoint
    $response = $this->getJson('/api/v1/categories/'.$category->slug);

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the category
    $response->assertJsonFragment($category->toArray());
});
