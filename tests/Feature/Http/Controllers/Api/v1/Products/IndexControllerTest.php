<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Support\Facades\Config;

it('can list products', function (): void {
    // Arrange: Create a product
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->published()->for($category)->create()->refresh();

    // Act: Send a GET request to the index endpoint
    $response = $this->getJson('/api/v1/products');

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the product
    $response->assertJsonFragment([$product->slug]);
});

it('returns the correct response structure for the list of products', function (): void {
    // Arrange: Create products
    $product = Product::factory()->published()
        ->for(Category::factory()->create())
        ->create();
    Media::factory()
        ->for($product, 'model')
        ->count(2)
        ->create([
            'collection_name' => Config::string('app.media_collections.products.name'),
            'disk' => Config::string('app.media_collections.products.disk'),
        ]);

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
});

it('return the correct total for the list of products', function (int $total): void {
    // Arrange: Create products
    Product::factory()->published()->for(Category::factory()->create())->count($total)->create();

    // Act: Send a GET request to the index endpoint
    $response = $this->getJson('/api/v1/products');

    // Assert: Check if the total is 30
    $response->assertJsonFragment(['total' => $total]);
})
    ->with([
        'Zero Product' => 0,
        'Many Product' => 30,
    ]);

it('can filter products by category', function (): void {
    // Arrange: Create a product
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->published()->for($category)->create()->refresh();
    $category2 = Category::factory()->create()->refresh();
    Product::factory()->published()->for($category2)->create()->refresh();

    // Act: Send a GET request to the index endpoint with the category filter
    $response = $this->getJson('/api/v1/products?category='.$category->slug);

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the product
    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.slug'))->toBe($product->slug)
        ->and($response->json('total'))->toBe(1);
});

it('can search products by title', function (): void {
    // Arrange: Create a product
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->published()->for($category)->create(['title' => 'product x'])->refresh();
    Product::factory()->published()->for($category)->create()->refresh();

    // Act: Send a GET request to the index endpoint with the search filter
    $response = $this->getJson('/api/v1/products?search='.$product->title);

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the product
    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.slug'))->toBe($product->slug)
        ->and($response->json('total'))->toBe(1);
});

it('can search products by summary', function (): void {
    // Arrange: Create a product
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->published()->for($category)->create(['summary' => 'summary product'])->refresh();
    Product::factory()->published()->for($category)->create()->refresh();

    // Act: Send a GET request to the index endpoint with the search filter
    $response = $this->getJson('/api/v1/products?search='.$product->summary);

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the product
    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.slug'))->toBe($product->slug)
        ->and($response->json('total'))->toBe(1);
});
