<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake(config('app.media_collections.products.disk'));
});

test('can add image to product', function () {
    $mediaCollection = config('app.media_collections.products.name');
    $mediaDisk = config('app.media_collections.products.disk');
    // Arrange
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    $file = UploadedFile::fake()->image('test-image.jpg');
    $product->addMedia($file)
        ->toMediaCollection($mediaCollection);
    $product->refresh();
    expect($product->getMedia($mediaCollection))->toHaveCount(1)
        ->and($product->getFirstMedia($mediaCollection)->file_name)->toBe('test-image.jpg');
    $media = $product->getFirstMedia($mediaCollection);
    Storage::disk($mediaDisk)->assertExists($media->id . '/' . $file->getClientOriginalPath());
});

test('can add multiple images to product', function () {
    $mediaCollection = config('app.media_collections.products.name');
    $mediaDisk = config('app.media_collections.products.disk');
    // Arrange
    $category = Category::factory()->create()->refresh();
    $product = Product::factory()->for($category)->create()->refresh();
    $files = [
        UploadedFile::fake()->image('image1.jpg'),
        UploadedFile::fake()->image('image2.jpg'),
        UploadedFile::fake()->image('image3.jpg'),
    ];

    // Act
    foreach ($files as $file) {
        $product->addMedia($file)->toMediaCollection($mediaCollection);
    }

    // Assert
    $product->refresh();
    expect($product->getMedia($mediaCollection))->toHaveCount(3);

    foreach ($product->getMedia($mediaCollection) as $media) {
        Storage::disk($mediaDisk)->assertExists($media->id . '/' . $media->file_name);
    }
});
