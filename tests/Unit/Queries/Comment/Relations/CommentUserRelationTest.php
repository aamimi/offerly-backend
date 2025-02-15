<?php

declare(strict_types=1);

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Comment;
use App\Models\User;
use App\Queries\Comment\Relations\CommentUserRelation;
use Illuminate\Database\Eloquent\Builder;

beforeEach(function (): void {
    $this->comment = Comment::factory()->create();
});

it('implements QueryFilterInterface', function (): void {
    $relation = new CommentUserRelation();
    expect($relation)->toBeInstanceOf(QueryFilterInterface::class);
});

it('returns builder instance', function (): void {
    $relation = new CommentUserRelation();
    $query = Comment::query();
    $result = $relation->apply($query);

    expect($result)->toBeInstanceOf(Builder::class);
});

it('loads user with specific columns', function (): void {
    // Arrange
    $user = User::factory()->create([
        'username' => 'testuser',
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
    $comment = Comment::factory()->for($user)->create();
    $relation = new CommentUserRelation();

    // Act
    $query = Comment::query()->where('id', $comment->id);
    $modifiedQuery = $relation->apply($query);
    $result = $modifiedQuery->first();

    // Assert
    expect($result->user)
        ->toBeInstanceOf(User::class)
        ->and($result->user->id)->toBe($user->id)
        ->and($result->user->username)->toBe('testuser')
        ->and($result->user->first_name)->toBe('John')
        ->and($result->user->last_name)->toBe('Doe')
        ->and($result->user->getAttributes())->toHaveKeys([
            'id',
            'username',
            'first_name',
            'last_name',
        ]);
});
