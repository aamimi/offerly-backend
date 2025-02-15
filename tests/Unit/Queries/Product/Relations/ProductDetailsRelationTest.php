<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Queries\Product\Relations\ProductDetailsRelation;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->product = Product::factory()->create();
});

it('implements QueryFilterInterface', function (): void {
    $relation = new ProductDetailsRelation();
    expect($relation)->toBeInstanceOf(QueryFilterInterface::class);
});

it('returns builder instance', function (): void {
    $relation = new ProductDetailsRelation();
    $query = Product::query();
    $result = $relation->apply($query);

    expect($result)->toBeInstanceOf(Builder::class);
});

it('loads product details with specific columns', function (): void {
    // Arrange
    $details = ProductDetail::factory()->for($this->product)->create([
        'description' => 'Test description',
        'conditions' => 'Test conditions',
        'instructions' => 'Test instructions',
    ]);
    $relation = new ProductDetailsRelation();

    // Act
    $query = Product::query();
    $modifiedQuery = $relation->apply($query);
    $product = $modifiedQuery->first();

    // Assert
    expect($product->details)
        ->toBeInstanceOf(ProductDetail::class)
        ->and($product->details->id)->toBe($details->id)
        ->and($product->details->description)->toBe('Test description')
        ->and($product->details->conditions)->toBe('Test conditions')
        ->and($product->details->instructions)->toBe('Test instructions')
        ->and($product->details->getAttributes())->toHaveKeys([
            'id',
            'product_id',
            'description',
            'conditions',
            'instructions',
        ]);
});

it('returns null details for product without details', function (): void {
    // Arrange
    $relation = new ProductDetailsRelation();

    // Act
    $query = Product::query();
    $modifiedQuery = $relation->apply($query);
    $product = $modifiedQuery->first();

    // Assert
    expect($product->details)->toBeNull();
});
