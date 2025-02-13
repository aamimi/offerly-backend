<?php

declare(strict_types=1);

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake(config('app.media_collections.products.disk'));
});

test('can add image to product', function (): void {
    $mediaCollection = config('app.media_collections.products.name');
    $mediaDisk = config('app.media_collections.products.disk');
    // Arrange
    $product = Product::factory()->create()->refresh();
    $file = UploadedFile::fake()->image('test-image.jpg');
    $product->addMedia($file)->toMediaCollection($mediaCollection);
    $product->refresh();
    // Assert
    expect($product->getMedia($mediaCollection))->toHaveCount(1)
        ->and($product->getFirstMedia($mediaCollection)->file_name)->toBe('test-image.jpg');
    $media = $product->getFirstMedia($mediaCollection);
    Storage::disk($mediaDisk)->assertExists($media->id.'/'.$file->getClientOriginalPath());
});

test('can add multiple images to product', function (): void {
    $mediaCollection = config('app.media_collections.products.name');
    $mediaDisk = config('app.media_collections.products.disk');
    // Arrange
    $product = Product::factory()->create()->refresh();
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
        Storage::disk($mediaDisk)->assertExists($media->id.'/'.$media->file_name);
    }
});
