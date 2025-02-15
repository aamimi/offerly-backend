<?php

declare(strict_types=1);

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\DTOs\Comment\IndexFilterDTO;
use App\Models\Comment;
use App\Services\Comment\IndexCommentService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

beforeEach(function (): void {
    $this->repository = mock(CommentRepositoryInterface::class);
    $this->service = new IndexCommentService($this->repository);
});

test('it can get paginated comments for a product', function (): void {
    // Arrange
    $request = Request::create(
        uri: '/',
        parameters: ['page' => 2, 'perPage' => 10],
    );

    $paginator = mock(Paginator::class);
    $paginator->allows([
        'items' => [
            Comment::factory()->make(['id' => 1]),
            Comment::factory()->make(['id' => 2]),
        ],
    ]);

    $this->repository
        ->expects('getCommentsOfProduct')
        ->withArgs(fn (IndexFilterDTO $filter): bool => $filter->slug === 'test-product'
            && $filter->page === 2
            && $filter->perPage === 10)
        ->once()
        ->andReturn($paginator);

    // Act
    $result = $this->service->handle($request, 'test-product');

    // Assert
    expect($result)
        ->toBeInstanceOf(Paginator::class)
        ->and($result->items())
        ->toHaveCount(2);
});

test('it uses default values when request parameters are not provided', function (): void {
    // Arrange
    $request = Request::create('/');

    $paginator = mock(Paginator::class);
    $paginator->allows([
        'items' => [
            Comment::factory()->make(['id' => 1]),
        ],
    ]);

    $this->repository
        ->expects('getCommentsOfProduct')
        ->withArgs(fn (IndexFilterDTO $filter): bool => $filter->slug === 'test-product'
            && $filter->page === 0
            && $filter->perPage === 5)
        ->once()
        ->andReturn($paginator);

    // Act
    $result = $this->service->handle($request, 'test-product');

    // Assert
    expect($result)
        ->toBeInstanceOf(Paginator::class)
        ->and($result->items())
        ->toHaveCount(1);
});
