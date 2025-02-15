<?php

declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\DTOs\Comment\IndexFilterDTO;
use App\Models\Comment;
use App\Queries\Comment\CommentQueryBuilder;
use App\Queries\Comment\Filters\ProductCommentsFilter;
use App\Queries\Comment\Relations\CommentUserRelation;
use Illuminate\Contracts\Pagination\Paginator;

final readonly class CommentRepository implements CommentRepositoryInterface
{
    /**
     * Get the comments of the product.
     *
     * @return Paginator<Comment>
     */
    public function getCommentsOfProduct(IndexFilterDTO $filter): Paginator
    {
        return (new CommentQueryBuilder())
            ->addFilter(new ProductCommentsFilter($filter->slug))
            ->addFilter(new CommentUserRelation())
            ->build()
            ->latest()
            ->simplePaginate(perPage: $filter->perPage, page: $filter->page);
    }
}
