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
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'path',
                'per_page',
                'to',
            ],
        ])
        ->assertJsonCount(3, 'data');
});
