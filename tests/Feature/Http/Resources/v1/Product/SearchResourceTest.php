<?php

declare(strict_types=1);

use App\Http\Resources\v1\Product\SearchResource;
use App\Models\Product;
use Illuminate\Http\Request;

it('transforms product resource into array with all fields')
    ->expect(fn (): array => new SearchResource(new Product([
        'slug' => 'product-1',
        'title' => 'Product 1',
        'price' => 100,
        'discount_price' => 80,
        'rating' => 4.5,
    ]))->toArray(new Request()))
    ->toBe([
        'slug' => 'product-1',
        'title' => 'Product 1',
        'price' => 100,
        'discount_price' => 80,
        'rating' => 4.5,
        'thumbnail' => null,
    ]);
