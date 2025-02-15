<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Comment;
use App\Models\Product;
use App\Queries\Comment\Filters\ProductCommentsFilter;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->query = Comment::query();
});

it('implements QueryFilterInterface', function (): void {
    $filter = ProductCommentsFilter::class;

    expect($filter)->toImplement(QueryFilterInterface::class);
});

it('returns instance of Builder', function (): void {
    $filter = new ProductCommentsFilter('test-slug');
    $result = $filter->apply($this->query);

    expect($result)->toBeInstanceOf(Builder::class);
});

it('filters comments by product slug and published status', function (): void {
    // Arrange
    $publishedProduct = Product::factory()->create([
        'slug' => 'published-product',
        'published_at' => now(),
    ]);
    $unpublishedProduct = Product::factory()->create([
        'slug' => 'unpublished-product',
        'published_at' => null,
    ]);

    $expectedComment = Comment::factory()->for($publishedProduct)->create();
    Comment::factory()->for($unpublishedProduct)->create();

    $filter = new ProductCommentsFilter('published-product');

    // Act
    $result = $filter->apply($this->query)->get();

    // Assert
    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->id)->toBe($expectedComment->id);
});

it('returns empty collection for non-existent product slug', function (): void {
    // Arrange
    $filter = new ProductCommentsFilter('non-existent-slug');

    // Act
    $result = $filter->apply($this->query)->get();

    // Assert
    expect($result)->toBeEmpty();
});

it('returns empty collection for unpublished product', function (): void {
    // Arrange
    $unpublishedProduct = Product::factory()->create([
        'slug' => 'unpublished-product',
        'published_at' => null,
    ]);
    Comment::factory()->for($unpublishedProduct)->create();

    $filter = new ProductCommentsFilter('unpublished-product');

    // Act
    $result = $filter->apply($this->query)->get();

    // Assert
    expect($result)->toBeEmpty();
});

it('maintains existing query constraints', function (): void {
    // Arrange
    $this->query->where('approved', true);
    $filter = new ProductCommentsFilter('test-slug');

    // Act
    $result = $filter->apply($this->query);

    // Assert
    $whereClauses = $result->getQuery()->wheres;
    expect($whereClauses)
        ->toHaveCount(2)
        ->and($whereClauses[0]['column'])->toBe('approved');
});
