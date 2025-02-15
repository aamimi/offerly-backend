<?php

declare(strict_types=1);

namespace App\Queries\Comment;

use App\Models\Comment;
use App\Queries\AbstractQueryBuilder;

/**
 * @extends AbstractQueryBuilder<Comment>
 */
final class CommentQueryBuilder extends AbstractQueryBuilder
{
    /**
     * Create a new query builder instance.
     */
    public function __construct()
    {
        parent::__construct(
            modelClass: Comment::class,
            columns: ['id', 'uuid', 'content', 'created_at', 'user_id', 'product_id']
        );
    }
}
