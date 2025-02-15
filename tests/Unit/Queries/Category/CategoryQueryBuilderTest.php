<?php

declare(strict_types=1);

use App\Models\Category;
use App\Queries\Category\CategoryQueryBuilder;

test('it initializes with correct model and columns', function (): void {
    // Act
    $builder = new CategoryQueryBuilder();
    $query = $builder->build();

    // Assert
    expect($query->getModel())
        ->toBeInstanceOf(Category::class)
        ->and($query->getQuery()->columns)
        ->toBe(['id', 'name', 'slug', 'parent_id', 'image_url', 'display_order']);
});
