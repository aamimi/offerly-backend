<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\ProductDetail;

it('to array', function (): void {
    $product = Product::factory()->create()->refresh();
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
    $product = Product::factory()->create()->refresh();
    $productDetail = ProductDetail::factory()->for($product)->create()->refresh();
    expect($productDetail->product)->toBeInstanceOf(Product::class)
        ->and($productDetail->product->is($product))->toBeTrue();
});
