<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    expect(array_keys($product->toArray()))->toEqual([
        'id',
        'slug',
        'title',
        'summary',
        'description',
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
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create(['published_at' => now()])->refresh();
    expect($product->published_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to category', function (): void {
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    expect($product->category)->toBeInstanceOf(Category::class)
        ->and($product->category->is($category))->toBeTrue();
});

it('has one meta tag', function (): void {
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    $metaTag = $product->metaTag()->create([
        'title' => 'Meta Title',
        'description' => 'Meta Description',
    ]);
    expect($product->metaTag)->toBeInstanceOf(App\Models\MetaTag::class)
        ->and($product->metaTag->is($metaTag))->toBeTrue();
});
