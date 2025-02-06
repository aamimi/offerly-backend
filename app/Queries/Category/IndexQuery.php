<?php

declare(strict_types=1);

namespace App\Queries\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

final readonly class IndexQuery
{
    /**
     * The limit of subcategories.
     */
    public const LIMIT = 5;

    /**
     * Get the query builder.
     *
     * @return Builder<Category>
     */
    public function builder(): Builder
    {
        return Category::query()
            ->select(['id', 'name', 'slug'])
            ->whereNull(columns: 'parent_id')
            ->orderBy(column: 'views', direction: 'desc')
            ->orderBy(column: 'display_order')
            ->with([
                'subcategories' => function (Relation $query): void {
                    $query->select(['id', 'name', 'slug', 'parent_id', 'image_url'])
                        ->orderBy(column: 'views', direction: 'desc')
                        ->orderBy(column: 'display_order')
                        ->limit(value: self::LIMIT);
                },
            ]);
    }
}
