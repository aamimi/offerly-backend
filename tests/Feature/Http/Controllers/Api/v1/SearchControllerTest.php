<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;

it('can search products and categories', function (bool $withProducts, bool $withCategories): void {
    // Arrange: Create test data
    if ($withProducts) {
        Product::factory()
            ->published()
            ->count(3)
            ->sequence(
                ['title' => 'Test Product 1'],
                ['title' => 'Test Product 2'],
                ['title' => 'Different Product']
            )
            ->create();
    }

    if ($withCategories) {
        Category::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Test Category 1'],
                ['name' => 'Test Category 2'],
                ['name' => 'Different Category']
            )
            ->create();
    }

    // Act: Send a GET request to the search endpoint
    $response = $this->getJson('/api/v1/search?query=Test');

    // Assert: Check if the response status is 200
    $response->assertStatus(200);

    // Assert: Check if the response format is correct
    $response->assertJson(
        fn (AssertableJson $json): AssertableJson => $json->has(
            'data',
            fn (AssertableJson $json): AssertableJson => $json->has('categories', fn (AssertableJson $json) => $json->when(
                $withCategories,
                fn ($json): AssertableJson => $json->count(2)
                    ->each(fn ($json) => $json->hasAll(['slug', 'name'])
                        ->where('name', fn ($name): bool => str_contains((string) $name, 'Test'))
                    )
            )
            )
                ->has('products', fn (AssertableJson $json) => $json->when(
                    $withProducts,
                    fn ($json): AssertableJson => $json->count(2)
                        ->each(fn ($json) => $json->hasAll([
                            'slug',
                            'title',
                            'price',
                            'discount_price',
                            'rating',
                            'thumbnail',
                        ])
                            ->where('title', fn ($title): bool => str_contains((string) $title, 'Test'))
                        )
                )
                )
        )
    );
})->with([
    ['withProducts' => true, 'withCategories' => true],
    ['withProducts' => false, 'withCategories' => false],
    ['withProducts' => true, 'withCategories' => false],
    ['withProducts' => false, 'withCategories' => true],
]);

it('returns empty results when no query parameter is provided', function (): void {
    // Act: Send a GET request without query parameter
    $response = $this->getJson('/api/v1/search');

    // Assert: Check response structure and empty results
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'categories' => [
                    '*' => [
                        'slug',
                        'name',
                    ],
                ],
                'products' => [
                    '*' => [
                        'slug',
                        'title',
                        'price',
                        'discount_price',
                        'rating',
                        'thumbnail',
                    ],
                ],
            ],
        ]);
});
