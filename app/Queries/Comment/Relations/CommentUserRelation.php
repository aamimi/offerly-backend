<?php

declare(strict_types=1);

namespace App\Queries\Comment\Relations;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements QueryFilterInterface<Comment>
 */
final class CommentUserRelation implements QueryFilterInterface
{
    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Comment>  $query
     * @return Builder<Comment>
     */
    public function apply(Builder $query): Builder
    {
        return $query->with('user:id,username,first_name,last_name');
    }
}
