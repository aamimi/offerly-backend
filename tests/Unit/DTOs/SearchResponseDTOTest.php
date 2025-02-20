<?php

declare(strict_types=1);

use App\DTOs\SearchResponseDTO;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

it('creates SearchResponseDTO with valid categories and products')
    ->expect(fn (): SearchResponseDTO => new SearchResponseDTO(
        new Collection([new Category(['name' => 'Category 1'])]),
        new Collection([new Product(['name' => 'Product 1'])])
    ))
    ->categories->toHaveCount(1)
    ->products->toHaveCount(1);

it('creates SearchResponseDTO with empty categories and products')
    ->expect(fn (): SearchResponseDTO => new SearchResponseDTO(
        new Collection([]),
        new Collection([])
    ))
    ->categories->toHaveCount(0)
    ->products->toHaveCount(0);

it('creates SearchResponseDTO with multiple categories and products')
    ->expect(fn (): SearchResponseDTO => new SearchResponseDTO(
        new Collection([new Category(['name' => 'Category 1']), new Category(['name' => 'Category 2'])]),
        new Collection([new Product(['name' => 'Product 1']), new Product(['name' => 'Product 2'])])
    ))
    ->categories->toHaveCount(2)
    ->products->toHaveCount(2);
