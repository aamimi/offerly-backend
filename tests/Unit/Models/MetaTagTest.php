<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\MetaTag;
use App\Models\Product;

test('to array', function (): void {
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    $metaTag = MetaTag::factory()->for($product, 'metaable')->create()->refresh();
    expect(array_keys($metaTag->toArray()))->toEqual([
        'id',
        'metaable_type',
        'metaable_id',
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'og_image',
        'x_title',
        'x_description',
        'x_image',
        'robots_follow',
        'robots_index',
        'canonical_url',
        'created_at',
        'updated_at',
    ]);
});

test('casts robots_follow and robots_index to boolean', function (): void {
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    $metaTag = MetaTag::factory()->for($product, 'metaable')->create([
        'robots_follow' => 1,
        'robots_index' => 1,
    ])->refresh();
    expect($metaTag->robots_follow)->toBeTrue()
        ->and($metaTag->robots_index)->toBeTrue();
});
