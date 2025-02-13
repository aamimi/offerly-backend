<?php

declare(strict_types=1);

use App\Models\Comment;
use App\Models\Product;

test('can fetch comments for a product', function (): void {
    $product = Product::factory()->published()->create(['slug' => 'test-product']);

    Comment::factory()->for($product)->count(3)->create();

    $response = $this->getJson('/api/v1/products/'.$product->slug.'/comments');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'content',
                    'created_at',
                    'user' => [
                        'username',
                        'first_name',
                        'last_name',
                    ],
                ],
            ],
        ])
        ->assertJsonCount(3, 'data');
});

test('returns 404 for non-existent product', function (): void {
    $response = $this->getJson('/api/v1/products/non-existent-product/comments');

    $response->assertStatus(404);
});
