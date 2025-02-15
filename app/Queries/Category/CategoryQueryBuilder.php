<?php

declare(strict_types=1);

namespace App\Queries\Category;

use App\Models\Category;
use App\Queries\AbstractQueryBuilder;

/**
 * @extends AbstractQueryBuilder<Category>
 */
final class CategoryQueryBuilder extends AbstractQueryBuilder
{
    /**
     * Create a new query builder instance.
     */
    public function __construct()
    {
        parent::__construct(
            modelClass: Category::class,
            columns: ['id', 'name', 'slug', 'parent_id', 'image_url', 'display_order']
        );
    }
}
