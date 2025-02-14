<?php

declare(strict_types=1);

use App\DTOs\Comment\IndexFilterDTO;
use App\Models\Comment;
use App\Models\Product;
use App\Queries\Comment\IndexQuery;

it('should return the query builder with the correct columns', function (): void {
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilterDTO('test'));
    expect($builder->getQuery()->columns)
        ->toBe(['id', 'uuid', 'content', 'created_at', 'user_id', 'product_id']);
});

it('should return correct number of comments', function (int $nbComment): void {
    $product = Product::factory()->published()->create()->refresh();
    Comment::factory()->count($nbComment)->for($product)->create();
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilterDTO($product->slug));
    /** @var Comment $comment */
    $comment = $builder->get()->first();
    expect($comment)->toBeInstanceOf(Comment::class)
        ->and($builder->get()->count())->toBe($nbComment)
        ->and($comment->product_id)->toBe($product->id);
})->with([5, 10, 15]);

it('should return only comments for published products', function (): void {
    $product = Product::factory()->create();
    Comment::factory()->count(5)->for($product)->create();
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilterDTO($product->slug));
    expect($builder->get()->count())->toBe(0);
});

it('should return comments ordered by latest', function (): void {
    $product = Product::factory()->published()->create()->refresh();
    Comment::factory()->count(5)->for($product)->create();
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilterDTO($product->slug));
    $comments = $builder->get();
    /** @var Comment $firstComment */
    $firstComment = $comments->first();
    /** @var Comment $lastComment */
    $lastComment = $comments->last();
    expect($firstComment->created_at->eq($lastComment->created_at))->toBeTrue();
});

it('should return comments with user relationship', function (): void {
    $product = Product::factory()->published()->create()->refresh();
    Comment::factory()->count(5)->for($product)->create();
    $query = new IndexQuery();
    $builder = $query->builder(new IndexFilterDTO($product->slug));
    /** @var Comment $comment */
    $comment = $builder->get()->first();
    expect($comment->user)->not()->toBeNull();
    expect($comment->user->only(['id', 'username', 'first_name', 'last_name']));
});
