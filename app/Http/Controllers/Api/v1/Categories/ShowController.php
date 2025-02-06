<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Categories;

use App\Models\Category;
use Illuminate\Http\JsonResponse;

final readonly class ShowController
{
    /**
     * Display the specified category.
     */
    public function __invoke(string $slug): JsonResponse
    {
        $category = Category::query()->where(column: 'slug', operator: '=', value: $slug)->firstOrFail();

        return response()->json($category);
    }
}
