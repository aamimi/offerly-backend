<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use App\Queries\Product\IndexQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should return the query builder with the correct columns', function (): void {
    $query = new IndexQuery();
    $builder = $query->builder(0, 10);
    expect($builder->getQuery()->columns)
        ->toBe(['id', 'slug', 'title', 'summary', 'price', 'discount_price', 'rating']);
});

it('should return correct number of products', function (int $nbProduct): void {
    $category = Category::factory()->create()->refresh();
    Product::factory()->for($category)->count($nbProduct)->create();
    $query = new IndexQuery();
    $builder = $query->builder(0, 10);
    $expectedCount = min($nbProduct, 10);
    expect($builder->get()->count())->toBe($expectedCount);
})->with([5, 10, 15]);
