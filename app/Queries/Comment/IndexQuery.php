<?php

declare(strict_types=1);

namespace App\Queries\Comment;

use App\Filters\Comment\IndexFilter;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;

final readonly class IndexQuery
{
    /**
     * Get the query builder.
     *
     * @return Builder<Comment>
     */
    public function builder(IndexFilter $filter): Builder
    {
        return Comment::query()
            ->select(['id', 'uuid', 'content', 'created_at', 'user_id', 'product_id'])
            ->whereHas('product', function (Builder $query) use ($filter): void {
                $query->whereNotNull('published_at')
                    ->where('slug', $filter->slug);
            })
            ->with('user:id,username,first_name,last_name')
            ->latest();
    }
}
