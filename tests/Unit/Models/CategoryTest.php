<?php

declare(strict_types=1);

use App\Models\Category;

test('to array', function (): void {
    $user = Category::factory()->create()->refresh();
    expect(array_keys($user->toArray()))->toEqual([
        'id',
        'parent_id',
        'slug',
        'name',
        'image_url',
        'meta_title',
        'meta_description',
        'display_order',
        'views',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to parent', function (): void {
    $parent = Category::factory()->create();
    $subcategory = Category::factory()->create();
    $subcategory->parent()->associate($parent)->save();
    expect($subcategory->parent)->toBeInstanceOf(Category::class)
        ->and($subcategory->parent->is($parent))->toBeTrue();
});

it('has many subcategories', function (): void {
    $category = Category::factory()->create();
    $subcategories = Category::factory()->count(3)->create(['parent_id' => $category->id]);
    expect($category->subcategories->count())->toBe(3);
    $category->subcategories->each(function ($subcategory) use ($subcategories): void {
        expect($subcategories->contains($subcategory))->toBeTrue();
    });
});
