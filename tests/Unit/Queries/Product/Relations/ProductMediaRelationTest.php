<?php

declare(strict_types=1);

use App\Models\Media;
use App\Models\Product;
use App\Queries\Product\Relations\ProductMediaRelation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

beforeEach(function (): void {
    $this->product = Product::factory()->create();
    $this->mediaCollection = 'test-collection';

    Config::set('app.media_collections.products.name', $this->mediaCollection);
});

it('implements ProductQueryInterface', function (): void {
    $relation = new ProductMediaRelation();
    expect($relation)->toBeInstanceOf(App\Contracts\Queries\ProductQueryInterface::class);
});

it('returns builder instance', function (): void {
    $relation = new ProductMediaRelation();
    $query = Product::query();
    $result = $relation->apply($query);

    expect($result)->toBeInstanceOf(Builder::class);
});

it('applies media relation with correct constraints', function (): void {
    // Arrange
    $limit = 1;
    $relation = new ProductMediaRelation($limit);
    Media::factory()->for($this->product, 'model')->create([
        'collection_name' => $this->mediaCollection,
        'order_column' => 2,
    ]);
    $media = Media::factory()->for($this->product, 'model')->create([
        'collection_name' => $this->mediaCollection,
        'order_column' => 1,
    ]);
    Media::factory()->for($this->product, 'model')->create([
        'collection_name' => 'other-collection',
        'order_column' => 1,
    ]);

    // Act
    $query = Product::query();
    $modifiedQuery = $relation->apply($query);
    $product = $modifiedQuery->first();
    expect($product->media->first()->uuid)->toBe($media->uuid)
        ->and($product->media->count())->toBe($limit);
});

it('respects the custom limit parameter', function (): void {
    // Arrange
    $limit = 3;
    $relation = new ProductMediaRelation(limit: $limit);
    Media::factory()->for($this->product, 'model')->count(5)->create([
        'collection_name' => $this->mediaCollection,
    ]);

    // Act
    $query = Product::query();
    $modifiedQuery = $relation->apply($query);
    $product = $modifiedQuery->first();
    expect($product->media->count())->toBe($limit);
});
