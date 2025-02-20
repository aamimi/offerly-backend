<?php

declare(strict_types=1);

use App\DTOs\SearchResponseDTO;
use App\Http\Resources\v1\SearchResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

it('transforms SearchResponseDTO with valid categories and products', function (): void {
    Category::factory()->create();
    Product::factory()->create();
    $r = new SearchResource(new SearchResponseDTO(
        Category::all(),
        Product::all()
    ))->toArray(new Request());
    expect($r)
        ->toHaveKeys(['categories', 'products']);
});
