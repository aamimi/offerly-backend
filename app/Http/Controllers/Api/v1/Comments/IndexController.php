<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Comments;

use App\Http\Resources\v1\Comment\IndexCollection;
use App\Models\Product;

final readonly class IndexController
{
    /**
     * Display a listing of the product comments.
     */
    public function __invoke(string $slug): IndexCollection
    {
        $product = Product::query()
            ->whereNotNull('published_at')
            ->where(column: 'slug', operator: '=', value: $slug)
            ->firstOrFail();

        $comments = $product->comments()
            ->with('user:id,username,first_name,last_name')
            ->latest()
            ->get();

        return new IndexCollection($comments);
    }
}
