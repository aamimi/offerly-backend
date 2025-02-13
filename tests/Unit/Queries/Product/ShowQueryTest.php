<?php

declare(strict_types=1);

use App\Models\Media;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Queries\Product\ShowQuery;
use Illuminate\Support\Facades\Config;

it('can get the published product by slug', function (): void {
    $slug = 'product-slug';
    $product = Product::factory()->published()->create(['slug' => $slug])->refresh();
    $query = new ShowQuery();
    expect($query->builder($slug)->first())->toBeInstanceOf(Product::class)
        ->and($query->builder($slug)->first()->id)->toBe($product->id);
});

it('return null if product does not exist', function (): void {
    $query = new ShowQuery();

    expect($query->builder('product-slug')->first())->toBeNull();
});

it('return null if product is not published', function (): void {
    $slug = 'product-slug';
    Product::factory()->create(['slug' => $slug]);
    $query = new ShowQuery();
    expect($query->builder($slug)->first())->toBeNull();
});

it('can get the product with the required columns', function (): void {
    $slug = 'product-slug';
    $product = Product::factory()->published()->create(['slug' => $slug])->refresh();

    $query = new ShowQuery();
    $fetchedProduct = $query->builder($slug)->first();
    expect($fetchedProduct)->toHaveKeys(
        [
            'id',
            'slug',
            'title',
            'summary',
            'price',
            'discount_price',
            'rating',
            'created_at',
        ]
    )
        ->and($fetchedProduct->id)->toBe($product->id)
        ->and($fetchedProduct->slug)->toBe($product->slug)
        ->and($fetchedProduct->title)->toBe($product->title)
        ->and($fetchedProduct->summary)->toBe($product->summary)
        ->and($fetchedProduct->price)->toBe($product->price)
        ->and($fetchedProduct->discount_price)->toBe($product->discount_price)
        ->and($fetchedProduct->rating)->toBe($product->rating)
        ->and($fetchedProduct->created_at->eq($product->created_at))->toBeTrue();
});

function createMedia(Product $product, int $order, ?string $collection = null): Media
{
    $collection ??= Config::get('app.media_collections.products.name');

    return Media::factory()->for($product, 'model')->create([
        'collection_name' => $collection,
        'order_column' => $order,
    ]);
}

it('should return product with media having the correct collection name and lowest order column', function (): void {
    $slug = 'product-slug';
    $product = Product::factory()->published()->create(['slug' => $slug])->refresh();
    $media2 = createMedia($product, 2);
    $media1 = createMedia($product, 1);
    $media3 = createMedia($product, 3);
    createMedia($product, 1, 'test');

    $query = new ShowQuery();
    $fetchedProduct = $query->builder($slug)->first();
    expect($fetchedProduct->media->count())->toBe(3)
        ->and($fetchedProduct->media[0]->uuid)->toBe($media1->uuid)
        ->and($fetchedProduct->media[1]->uuid)->toBe($media2->uuid)
        ->and($fetchedProduct->media[2]->uuid)->toBe($media3->uuid);
});

it('should return product with product details', function (): void {
    $slug = 'product-slug';
    $product = Product::factory()->published()->create(['slug' => $slug])->refresh();
    $productDetails = ProductDetail::factory()->for($product)->create()->refresh();
    $query = new ShowQuery();
    $fetchedProduct = $query->builder($slug)->first();
    expect($fetchedProduct->details)->is($productDetails)
        ->and($fetchedProduct->details)->tohaveKeys(['id', 'product_id', 'description', 'instructions', 'conditions']);
});
