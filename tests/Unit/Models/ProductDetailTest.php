<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;

it('to array', function (): void {
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    $productDetail = ProductDetail::factory()->for($product)->create()->refresh();
    expect(array_keys($productDetail->toArray()))->toEqual([
        'id',
        'product_id',
        'description',
        'conditions',
        'instructions',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to product', function (): void {
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    $productDetail = ProductDetail::factory()->for($product)->create()->refresh();
    expect($productDetail->product)->toBeInstanceOf(Product::class)
        ->and($productDetail->product->is($product))->toBeTrue();
});
