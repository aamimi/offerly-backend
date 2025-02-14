<?php

declare(strict_types=1);

use App\DTOs\Comment\IndexFilterDTO;

it('can be constructed', function (): void {
    $filter = new IndexFilterDTO('slug');

    expect($filter->slug)->toBe('slug');
});
