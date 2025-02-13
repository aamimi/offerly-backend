<?php

declare(strict_types=1);

use App\Filters\Product\IndexFilter;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Queries\Product\IndexQuery;
use Illuminate\Support\Facades\Config;

it('should return the query builder with the correct columns', function (): void {
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilter());
    expect($builder->getQuery()->columns)
        ->toBe(['id', 'slug', 'title', 'summary', 'price', 'discount_price', 'rating']);
});

it('should return correct number of products', function (int $nbProduct): void {
    Product::factory()->published()->count($nbProduct)->create();
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilter());
    $expectedCount = min($nbProduct, 10);
    expect($builder->get()->count())->toBe($expectedCount);
})->with([5, 10, 15]);

it('should return only published products', function (): void {
    Product::factory()->published()->create();
    Product::factory()->create();
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilter());
    expect($builder->get()->count())->toBe(1);
});

it('should return the correct number of products when skipping', function (): void {
    Product::factory()->published()->count(15)->create();
    $query = new IndexQuery();
    expect($query->builder(new IndexFilter(10, 10))->get()->count())->toBe(5)
        ->and($query->builder(new IndexFilter(20, 10))->get()->count())->toBe(0)
        ->and($query->builder(new IndexFilter())->get()->count())->toBe(10);
});

it('should return the products filtered by category parent', function (): void {
    $category = Category::factory()->create()->refresh();
    $category2 = Category::factory()->create()->refresh();
    Product::factory()->published()->for($category)->count(5)->create();
    Product::factory()->published()->for($category2)->count(5)->create();
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilter(categorySlug: $category->slug));
    expect($builder->get()->count())->toBe(5);
});

it('should return products from the category and its subcategories', function (): void {
    $category = Category::factory()->create()->refresh();
    $subcategory = Category::factory()->create(['parent_id' => $category->id])->refresh();
    Product::factory()->published()->for($category)->count(5)->create();
    Product::factory()->published()->for($subcategory)->count(5)->create();
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilter(categorySlug: $category->slug));
    expect($builder->get()->count())->toBe(10);
});

it(
    'should return products filtered by search with title field (case insensitive)',
    function (string $search, int $expected): void {
        $category = Category::factory()->create()->refresh();
        Product::factory()->published()->for($category)->create(['title' => 'Product 1']);
        Product::factory()->published()->for($category)->create(['title' => 'Product 2']);
        Product::factory()->published()->for($category)->create(['title' => 'Product 3']);
        Product::factory()->published()->for($category)->create(['title' => 'processes 3']);
        $query = new IndexQuery();
        $builder = $query->builder(new IndexFilter(search: $search));
        expect($builder->get()->count())->toBe($expected);
    }
)
    ->with([
        ['search' => 'product 1', 'expected' => 1],
        ['search' => 'product 2', 'expected' => 1],
        ['search' => 'product 3', 'expected' => 1],
        ['search' => 'PRODUCT 1', 'expected' => 1],
        ['search' => 'PRODUCT 2', 'expected' => 1],
        ['search' => 'PRODUCT 3', 'expected' => 1],
        ['search' => 'PRODUCT 4', 'expected' => 0],
        ['search' => 'PRODUCT', 'expected' => 3],
        ['search' => 'pRo', 'expected' => 4],
    ]);

it(
    'should return products filtered by search with summary field (case insensitive)',
    function (string $search, int $expected): void {
        $category = Category::factory()->create()->refresh();
        Product::factory()->published()->for($category)->create(['summary' => 'Summary 1']);
        Product::factory()->published()->for($category)->create(['summary' => 'Summary 2']);
        Product::factory()->published()->for($category)->create(['summary' => 'Summary 3']);
        Product::factory()->published()->for($category)->create(['summary' => 'processes 3']);
        $query = new IndexQuery();
        $builder = $query->builder(new IndexFilter(search: $search));
        expect($builder->get()->count())->toBe($expected);
    }
)->with([
    ['search' => 'summary 1', 'expected' => 1],
    ['search' => 'SUmmARY 1', 'expected' => 1],
    ['search' => 'SUMMARY 2', 'expected' => 1],
    ['search' => 'suMMARY 3', 'expected' => 1],
    ['search' => 'SUMMARY 4', 'expected' => 0],
    ['search' => 'SUMMARY', 'expected' => 3],
    ['search' => 's', 'expected' => 4],
]);

it('should return products with media having the correct collection name and lowest order column', function (): void {
    $product = Product::factory()->published()->create();
    Media::factory()->for($product, 'model')->create([
        'collection_name' => Config::string('app.media_collections.products.name'),
        'order_column' => 2,
    ]);
    $media = Media::factory()->for($product, 'model')->create([
        'collection_name' => Config::string('app.media_collections.products.name'),
        'order_column' => 1,
    ]);
    Media::factory()->for($product, 'model')->create([
        'collection_name' => 'test',
        'order_column' => 1,
    ]);
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilter());
    $product = $builder->first();
    expect($product->media->first()->uuid)->toBe($media->uuid);
});
