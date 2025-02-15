<?php

declare(strict_types=1);

namespace App\Queries\Comment\Filters;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements QueryFilterInterface<Comment>
 */
final readonly class ProductCommentsFilter implements QueryFilterInterface
{
    /**
     * ProductCommentsFilter constructor.
     */
    public function __construct(private string $slug) {}

    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Comment>  $query
     * @return Builder<Comment>
     */
    public function apply(Builder $query): Builder
    {
        return $query->whereHas('product', function (Builder $query): void {
            $query->whereNotNull('published_at')
                ->where(column: 'slug', operator: '=', value: $this->slug);
        });
    }
}
