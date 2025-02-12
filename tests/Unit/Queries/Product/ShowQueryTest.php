<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use App\Queries\Product\ShowQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can get the published product by slug', function (): void {
    $slug = 'product-slug';
    $query = new ShowQuery();

    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->published()->for($category)->create(['slug' => $slug])->refresh();

    expect($query->builder($slug)->first())->toBeInstanceOf(Product::class)
        ->and($query->builder($slug)->first()->id)->toBe($product->id);
});

it('return null if product does not exist', function (): void {
    $query = new ShowQuery();

    expect($query->builder('product-slug')->first())->toBeNull();
});

it('return null if product is not published', function (): void {
    $slug = 'product-slug';
    $query = new ShowQuery();

    $category = Category::factory()->create()->refresh();
    Product::factory()->for($category)->create(['slug' => $slug]);

    expect($query->builder($slug)->first())->toBeNull();
});

it('can get the product with the required columns', function (): void {
    $slug = 'product-slug';
    $query = new ShowQuery();

    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->published()->for($category)->create(['slug' => $slug])->refresh();

    $fetchedProduct = $query->builder($slug)->first();
    expect($fetchedProduct)->toHaveKeys(
        ['id', 'slug', 'title', 'description', 'price', 'discount_price', 'rating', 'created_at']
    )
        ->and($fetchedProduct->id)->toBe($product->id)
        ->and($fetchedProduct->slug)->toBe($product->slug)
        ->and($fetchedProduct->title)->toBe($product->title)
        ->and($fetchedProduct->description)->toBe($product->description)
        ->and($fetchedProduct->price)->toBe($product->price)
        ->and($fetchedProduct->discount_price)->toBe($product->discount_price)
        ->and($fetchedProduct->rating)->toBe($product->rating)
        ->and($fetchedProduct->created_at->eq($product->created_at))->toBeTrue();
});
