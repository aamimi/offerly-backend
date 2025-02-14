<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Products;

use App\Http\Resources\v1\Product\ShowResource;
use App\Models\Product;
use App\Services\Product\ShowProductService;
use Illuminate\Http\JsonResponse;

final readonly class ShowController
{
    /**
     * ShowController constructor.
     */
    public function __construct(private ShowProductService $productService) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug): ShowResource|JsonResponse
    {
        $product = $this->productService->handle($slug);
        if (! $product instanceof Product) {
            return new JsonResponse(['message' => 'Product not found.'], 404);
        }

        return new ShowResource($product);
    }
}
