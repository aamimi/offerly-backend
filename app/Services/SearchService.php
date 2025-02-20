<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTOs\SearchResponseDTO;
use Illuminate\Http\Request;

final readonly class SearchService
{
    /**
     * Create a new service instance.
     */
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Handle the search request.
     */
    public function handle(Request $request): SearchResponseDTO
    {
        $searchTerm = $request->query('query');
        $searchTerm = is_string($searchTerm) ? $searchTerm : null;

        return new SearchResponseDTO(
            categories: $this->categoryRepository->getCategoriesByName($searchTerm),
            products: $this->productRepository->getPublishedProductsByTitle($searchTerm)
        );
    }
}
