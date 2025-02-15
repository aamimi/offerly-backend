<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DTOs\Comment\IndexFilterDTO;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\Paginator;

interface CommentRepositoryInterface
{
    /**
     * Get the comments of the product.
     *
     * @return Paginator<Comment>
     */
    public function getCommentsOfProduct(IndexFilterDTO $filter): Paginator;
}
