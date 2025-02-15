<?php

declare(strict_types=1);

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use App\Services\Category\ShowCategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function (): void {
    $this->repository = mock(CategoryRepositoryInterface::class);
    $this->service = new ShowCategoryService($this->repository);
});

test('it can get a parent category by slug', function (): void {
    // Arrange
    $category = Category::factory()->make([
        'id' => 1,
        'slug' => 'test-category',
    ]);

    $this->repository
        ->expects('getParentCategoryBySlug')
        ->with('test-category')
        ->once()
        ->andReturn($category);

    // Act
    $result = $this->service->handle('test-category');

    // Assert
    expect($result)
        ->toBeInstanceOf(Category::class)
        ->and($result->id)->toBe(1)
        ->and($result->slug)->toBe('test-category');
});

test('it throws model not found exception when category does not exist', function (): void {
    // Arrange
    $this->repository
        ->expects('getParentCategoryBySlug')
        ->with('non-existent')
        ->once()
        ->andReturnNull();

    // Act & Assert
    expect(fn () => $this->service->handle('non-existent'))
        ->toThrow(ModelNotFoundException::class);
});
