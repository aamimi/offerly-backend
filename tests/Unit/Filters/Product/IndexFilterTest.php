<?php

declare(strict_types=1);

use App\Filters\Product\IndexFilter;

it('can be constructed', function (): void {
    $filter = new IndexFilter(skip: 5, limit: 10, search: 'test', categoriesIds: [1, 2, 3]);

    expect($filter->skip)->toBe(5)
        ->and($filter->limit)->toBe(10)
        ->and($filter->search)->toBe('test')
        ->and($filter->categoriesIds)->toBe([1, 2, 3]);
});
