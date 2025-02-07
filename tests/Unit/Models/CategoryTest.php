<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Support\Facades\Config;

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

it('returns the correct image URL', function (): void {
    $catWithImage = Category::factory()->create(['image_url' => 'http://example.com/image.jpg']);
    expect($catWithImage->getImageUrl())->toBe('http://example.com/image.jpg');

    $catWithoutImage = Category::factory()->create(['image_url' => null]);
    expect($catWithoutImage->getImageUrl())->toBe(asset(Config::string('app.default_images.category')));
})->note('This test is incomplete check if using s3.');
