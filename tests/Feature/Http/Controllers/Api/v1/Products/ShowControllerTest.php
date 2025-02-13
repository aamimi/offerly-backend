<?php

declare(strict_types=1);

use App\Models\Media;
use App\Models\MetaTag;
use App\Models\Product;
use App\Models\ProductDetail;

it('can show a product', function (bool $withMedia, bool $withMetaTag): void {
    // Arrange: Create a product
    $product = Product::factory()->published()->create()->refresh();
    ProductDetail::factory()->for($product)->create();
    if ($withMedia) {
        Media::factory()->for($product, 'model')->forProduct()->count(4)->create();
    }

    if ($withMetaTag) {
        MetaTag::factory()->for($product, 'metaable')->create()->refresh();
    }

    // Act: Send a GET request to the show endpoint
    $response = $this->getJson('/api/v1/products/'.$product->slug);

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response contains the product
    $response->assertJsonFragment([$product->slug]);

    // Assert: Check if the response format is correct
    $response->assertJsonStructure([
        'data' => [
            'slug',
            'title',
            'summary',
            'description',
            'conditions',
            'instructions',
            'price',
            'discount_price',
            'rating',
            'created_at',
            'has_comments',
            'images' => [
                '*' => [
                    'url',
                    'name',
                    'file_name',
                    'size',
                    'mime_type',
                ],
            ],
        ],
        'meta' => [
            'title',
            'description',
            'keywords',
            'og_title',
            'og_description',
            'og_image',
            'x_title',
            'x_description',
            'x_image',
            'robots_follow',
            'robots_index',
            'canonical_url',
        ],
    ]);
})->with([
    ['withMedia' => true, 'withMetaTag' => true],
    ['withMedia' => false, 'withMetaTag' => false],
    ['withMedia' => true, 'withMetaTag' => false],
    ['withMedia' => false, 'withMetaTag' => true],
]);

it('return 404 if product does not exist', function (): void {
    // Act: Send a GET request to the show endpoint
    $response = $this->getJson('/api/v1/products/invalid-slug');

    // Assert: Check if the response status is 404
    $response->assertStatus(404);
});

it('return 404 if product is not published', function (): void {
    // Arrange: Create a product
    $product = Product::factory()->create()->refresh();

    // Act: Send a GET request to the show endpoint
    $response = $this->getJson('/api/v1/products/'.$product->slug);

    // Assert: Check if the response status is 404
    $response->assertStatus(404);
});
