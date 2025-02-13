<?php

declare(strict_types=1);

use App\Http\Resources\v1\Product\ShowResource;
use App\Models\Category;
use App\Models\Media;
use App\Models\MetaTag;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

it('returns correct data structure', function (): void {
    // Arrange: Create a product
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    ProductDetail::factory()->for($product)->create();

    // Act: Create a ShowResource instance and get the data
    $resource = new ShowResource($product);
    $request = Request::create('/api/v1/products/'.$product->slug);
    $data = $resource->toArray($request);

    // Assert: Check if the data has the correct structure
    expect($data)->toHaveKeys(
        [
            'slug',
            'title',
            'summary',
            'description',
            'conditions',
            'instructions',
            'price',
            'discount_price',
            'images',
            'rating',
            'created_at',
        ]
    )->and($data['images'])->toBeArray()
        ->and($data['images'])->toHaveCount(0);
});

it('returns correct data structure with images', function (): void {
    // Arrange: Create a product with images
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    ProductDetail::factory()->for($product)->create();
    Media::factory()->for($product, 'model')->forProduct()->count(4)->create();

    // Act: Create a ShowResource instance and get the data
    $resource = new ShowResource($product);
    $request = Request::create('/api/v1/products/'.$product->slug);
    $data = $resource->toArray($request);

    // Assert: Check if the data has the correct structure
    expect($data)->toHaveKeys(
        [
            'slug',
            'title',
            'summary',
            'description',
            'conditions',
            'instructions',
            'price',
            'discount_price',
            'images',
            'rating',
            'created_at',
        ]
    )->and($data['images'])->toBeArray()
        ->and($data['images'])->toHaveCount(4)
        ->and($data['images'][0])->toHaveKeys(
            ['url', 'name', 'file_name', 'size', 'mime_type']
        );
});

it(
    'returns correct meta data with the resource',
    function (array $productAttributes, array $metaData, array $expectedMeta): void {
        // Arrange: Create a product with meta tags
        $category = Category::factory()->create()->refresh();
        $product = Product::factory()->for($category)->create($productAttributes)->refresh();
        ProductDetail::factory()->for($product)->create();
        MetaTag::factory()->for($product, 'metaable')->create($metaData)->refresh();

        // Act: Create a ShowResource instance and get the additional data
        $resource = new ShowResource($product);
        $request = Request::create('/api/v1/products/'.$product->slug);
        $additionalData = $resource->with($request);
        // Assert: Check if the additional data contains the correct meta information
        expect($additionalData)->toHaveKey('meta')
            ->and($additionalData['meta'])->toMatchArray($expectedMeta);
    }
)->with([
    'first test' => [
        'productAttributes' => [
            'title' => 'Test Product',
            'summary' => 'Test Summary',
        ],
        'metaData' => [
            'title' => 'Meta Title',
            'description' => 'Meta Description',
            'og_title' => 'OG Title',
            'x_title' => 'X Title',
            'og_description' => 'OG Description',
            'x_description' => 'X Description',
        ],
        'expectedMeta' => [
            'title' => 'Meta Title',
            'description' => 'Meta Description',
            'og_title' => 'OG Title',
            'x_title' => 'X Title',
            'og_description' => 'OG Description',
            'x_description' => 'X Description',
        ],
    ],
    'second test' => [
        'productAttributes' => [
            'title' => 'Test Product',
            'summary' => 'Test Summary',
        ],
        'metaData' => [
            'title' => null,
            'description' => null,
            'og_title' => null,
            'x_title' => null,
            'og_description' => null,
            'x_description' => null,
        ],
        'expectedMeta' => [
            'title' => 'Test Product',
            'description' => 'Test Summary',
            'og_title' => 'Test Product',
            'x_title' => 'Test Product',
            'og_description' => 'Test Summary',
            'x_description' => 'Test Summary',
        ],
    ],
    'third test' => [
        'productAttributes' => [
            'title' => 'Test Product',
            'summary' => 'Test Summary',
        ],
        'metaData' => [
            'title' => null,
            'description' => null,
            'og_title' => 'OG Title',
            'x_title' => 'X Title',
            'og_description' => 'OG Description',
            'x_description' => 'X Description',
        ],
        'expectedMeta' => [
            'title' => 'Test Product',
            'description' => 'Test Summary',
            'og_title' => 'OG Title',
            'x_title' => 'X Title',
            'og_description' => 'OG Description',
            'x_description' => 'X Description',
        ],
    ],
]);
