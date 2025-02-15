<?php

declare(strict_types=1);

use App\DTOs\Comment\IndexFilterDTO;

it('can be constructed', function (): void {
    $filter = new IndexFilterDTO('slug', 1, 5);

    expect($filter->slug)->toBe('slug')
        ->and($filter->page)->toBe(1)
        ->and($filter->perPage)->toBe(5);
});
