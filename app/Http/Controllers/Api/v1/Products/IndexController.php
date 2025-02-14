<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Products;

use App\Http\Resources\v1\Product\IndexCollection;
use App\Services\Product\IndexProductService;
use Illuminate\Http\Request;

final readonly class IndexController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private IndexProductService $indexProductService) {}

    /**
     * List of products.
     */
    public function __invoke(Request $request): IndexCollection
    {
        $result = $this->indexProductService->handle($request);

        return (new IndexCollection($result->products))
            ->additional([
                'total' => $result->total,
                'skip' => $request->query(key: 'skip', default: '0'),
                'limit' => $request->query(key: 'limit', default: '10'),
            ]);
    }
}
