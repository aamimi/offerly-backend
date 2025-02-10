<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list products', function (): void {
    // Arrange: Create a product
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();

    // Act: Send a GET request to the index endpoint
    $response = $this->getJson('/api/v1/products');

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the product
    $response->assertJsonFragment([$product->slug]);
});

it('returns the correct response structure for the list of products', function (): void {
    // Act: Send a GET request to the index endpoint
    $response = $this->getJson('/api/v1/products');

    // Assert: Check if the response format is correct
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'slug',
                'title',
                'summary',
                'price',
                'discount_price',
                'thumbnail' => [
                    'url',
                    'name',
                    'file_name',
                    'size',
                    'mime_type',
                ],
                'rating',
            ],
        ],
        'total',
        'skip',
        'limit',
    ]);
})->with([
    'One Product' => fn () => Product::factory()->for(Category::factory()->create())->create(),
    'Many Product' => fn () => Product::factory()->for(Category::factory()->create())->count(30)->create(),
]);
