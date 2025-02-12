<?php

declare(strict_types=1);

use App\Filters\Product\IndexFilter;
use App\Models\Category;

it('can get the category', function (): void {
    $slug = 'category-slug';
    $filter = new IndexFilter(categorySlug: $slug);

    $category = Category::factory()->create(['slug' => $slug]);

    expect($filter->getCategory())->toBeInstanceOf(Category::class)
        ->and($filter->getCategory()->id)->toBe($category->id);
});

it('return null if category does not exist', function (): void {
    $filter = new IndexFilter(categorySlug: 'category-slug');

    expect($filter->getCategory())->toBeNull();
});

it('can get the category ids', function (): void {
    $slug = 'category-slug';
    $filter = new IndexFilter(categorySlug: $slug);

    $category = Category::factory()->create(['slug' => $slug]);

    expect($filter->getCategoriesIds())->toBeArray()
        ->and($filter->getCategoriesIds())->toBe([$category->id]);
});

it('can get the category and it subcategories ids', function (): void {
    $slug = 'category-slug';
    $filter = new IndexFilter(categorySlug: $slug);

    $category = Category::factory()->create(['slug' => $slug]);
    $subcategory = Category::factory()->create(['parent_id' => $category->id]);

    expect($filter->getCategoriesIds())->toBeArray()
        ->and($filter->getCategoriesIds())->toBe([$subcategory->id, $category->id]);
});

it('return getCategoriesIds null if category does not exist', function (): void {
    $filter = new IndexFilter(categorySlug: 'category-slug');

    expect($filter->getCategoriesIds())->toBeNull();
});
