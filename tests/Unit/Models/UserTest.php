<?php

declare(strict_types=1);

use App\Models\Comment;
use App\Models\User;

test('to array', function (): void {
    $user = User::factory()->create()->refresh();
    expect(array_keys($user->toArray()))->toEqual([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
    ]);
});

test('a user can have multiple comments', function (): void {
    $user = User::factory()->create();

    Comment::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    expect($user->comments)->toHaveCount(3)
        ->and($user->comments->first())->toBeInstanceOf(Comment::class);
});
