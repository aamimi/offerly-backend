<?php

declare(strict_types=1);

use App\Http\Resources\v1\Category\SearchResource;
use App\Models\Category;
use Illuminate\Http\Request;

it('transforms category resource into array with name and slug')
    ->expect(fn (): array => new SearchResource(new Category(['name' => 'Category 1', 'slug' => 'category-1']))
        ->toArray(new Request()))
    ->toBe([
        'name' => 'Category 1',
        'slug' => 'category-1',
    ]);
