<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $product = Product::factory()->create()->refresh();
    expect(array_keys($product->toArray()))->toEqual([
        'id',
        'slug',
        'title',
        'summary',
        'price',
        'discount_price',
        'rating',
        'views',
        'category_id',
        'published_at',
        'created_at',
        'updated_at',
    ]);
});

test('casts published_at to Carbon instance', function (): void {
    $product = Product::factory()->create(['published_at' => now()])->refresh();
    expect($product->published_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to category', function (): void {
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    expect($product->category)->toBeInstanceOf(Category::class)
        ->and($product->category->is($category))->toBeTrue();
});

it('has one meta tag', function (): void {
    $product = Product::factory()->create()->refresh();
    $metaTag = $product->metaTag()->create([
        'title' => 'Meta Title',
        'description' => 'Meta Description',
    ]);
    expect($product->metaTag)->toBeInstanceOf(App\Models\MetaTag::class)
        ->and($product->metaTag->is($metaTag))->toBeTrue();
});

it('has one product detail', function (): void {
    $product = Product::factory()->create()->refresh();
    $productDetail = $product->details()->create([
        'description' => 'Product Description',
        'conditions' => 'Product Conditions',
        'instructions' => 'Product Instructions',
    ]);
    expect($product->details)->toBeInstanceOf(App\Models\ProductDetail::class)
        ->and($product->details->is($productDetail))->toBeTrue();
});

test('a product can have multiple comments', function (): void {
    $product = Product::factory()->create();

    Comment::factory()->count(3)->create([
        'product_id' => $product->id,
    ]);

    expect($product->comments)->toHaveCount(3)
        ->and($product->comments->first())->toBeInstanceOf(Comment::class);
});
