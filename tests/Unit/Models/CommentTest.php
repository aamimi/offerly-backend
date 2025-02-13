<?php

declare(strict_types=1);

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;

test('a comment belongs to a user', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'content' => 'Test comment',
    ]);

    expect($comment->user)->toBeInstanceOf(User::class)
        ->and($comment->user->id)->toBe($user->id);
});

test('a comment belongs to a product', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'content' => 'Test comment',
    ]);

    expect($comment->product)->toBeInstanceOf(Product::class)
        ->and($comment->product->id)->toBe($product->id);
});

test('a comment has required fields', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'content' => 'Test comment',
    ]);

    expect($comment->content)->not->toBeEmpty()
        ->and($comment->user_id)->not->toBeNull()
        ->and($comment->product_id)->not->toBeNull()
        ->and($comment->uuid)->not->toBeNull();
});

test('comment can store emoji', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $commentWithEmoji = 'Great product! 👍 ❤️ 🎉';

    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'content' => $commentWithEmoji,
    ]);

    expect($comment->fresh()->content)->toBe($commentWithEmoji);
});

test('comment well be deleted when user is deleted', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $user->delete();

    expect(Comment::query()->find($comment->id))->toBeNull();
});

test('comment well be deleted when product is deleted', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $product->delete();

    expect(Comment::query()->find($comment->id))->toBeNull();
});
