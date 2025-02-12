<?php

declare(strict_types=1);

namespace App\Queries\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

final readonly class ShowQuery
{
    /**
     * Get the query builder.
     *
     * @return Builder<Category>
     */
    public function builder(string $slug): Builder
    {
        return Category::query()
            ->select(['id', 'name', 'slug'])
            ->whereNull(columns: 'parent_id')
            ->where(column: 'slug', operator: '=', value: $slug)
            ->with([
                'subcategories' => function (Relation $query): void {
                    $query->select(['id', 'name', 'slug', 'parent_id', 'image_url'])
                        ->orderBy(column: 'name');
                },
                'metaTag',
            ]);
    }
}
