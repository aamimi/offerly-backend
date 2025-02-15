<?php

declare(strict_types=1);

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use App\Services\Category\IndexCategoryService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function (): void {
    $this->repository = mock(CategoryRepositoryInterface::class);
    $this->service = new IndexCategoryService($this->repository);
});

test('it can get parent categories', function (): void {
    // Arrange
    $categories = Collection::make([
        Category::factory()->make(['id' => 1]),
        Category::factory()->make(['id' => 2]),
    ]);

    $this->repository
        ->expects('getParentCategories')
        ->once()
        ->andReturn($categories);

    // Act
    $result = $this->service->handle();

    // Assert
    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->and($result->pluck('id')->toArray())
        ->toBe([1, 2]);
});
