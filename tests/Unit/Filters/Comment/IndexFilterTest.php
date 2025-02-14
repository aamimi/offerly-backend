<?php

declare(strict_types=1);

use App\Filters\Comment\IndexFilter;

it('can be constructed', function (): void {
    $filter = new IndexFilter('slug');

    expect($filter->slug)->toBe('slug');
});
