<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Products;

use App\Http\Resources\v1\Product\IndexCollection;
use App\Models\Product;
use App\Queries\Product\IndexQuery;
use Illuminate\Http\Request;

final readonly class IndexController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private IndexQuery $query) {}

    /**
     * List of products.
     */
    public function __invoke(Request $request): IndexCollection
    {
        $skip = (int) $request->query(key: 'skip', default: '0');
        $limit = (int) $request->query(key: 'limit', default: '10');
        $products = $this->query->builder($skip, $limit)->get();

        return (new IndexCollection($products))
            ->additional([
                'total' => Product::query()->count(),
                'skip' => $skip,
                'limit' => $limit,
            ]);
    }
}
