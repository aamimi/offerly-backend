<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;

arch()->preset()->php();
arch()->preset()->laravel();
arch()->preset()->security();

arch('controllers')
    ->expect('App\Http\Controllers')
    ->not->toBeUsed();

arch('avoid mutation')
    ->expect('App')
    ->classes()
    ->toBeReadonly()
    ->ignoring([
        'App\Exceptions',
        'App\Jobs',
        'App\Models',
        'App\Providers',
        'App\Services',
        'App\Http\Requests',
        'App\Http\Resources',
        'App\Queries',
    ]);

arch('avoid inheritance')
    ->expect('App')
    ->classes()
    ->toExtendNothing()
    ->ignoring([
        'App\Models',
        'App\Exceptions',
        'App\Jobs',
        'App\Providers',
        'App\Services',
        'App\Http\Requests',
        'App\Http\Resources',
        App\Queries\Product\ProductQueryBuilder::class,
        App\Queries\Comment\CommentQueryBuilder::class,
        App\Queries\Category\CategoryQueryBuilder::class,
    ]);

arch('annotations')
    ->expect('App')
    ->toHavePropertiesDocumented()
    ->toHaveMethodsDocumented();

arch('avoid open for extension')
    ->expect('App')
    ->classes()
    ->toBeFinal()
    ->ignoring([
        App\Queries\AbstractQueryBuilder::class,
    ]);

arch('avoid abstraction')
    ->expect('App')
    ->not->toBeAbstract()
    ->ignoring([
        'App\Contracts',
        App\Queries\AbstractQueryBuilder::class,
    ]);

arch('factories')
    ->expect('Database\Factories')
    ->toExtend(Factory::class)
    ->toHaveMethod('definition')
    ->toOnlyBeUsedIn([
        'App\Models',
    ]);

arch('models')
    ->expect('App\Models')
    ->toHaveMethod('casts')
    ->toOnlyBeUsedIn([
        'App\Http',
        'App\Jobs',
        'App\Models',
        'App\Providers',
        'App\Actions',
        'App\Services',
        'Database\Factories',
        'Database\Seeders',
        'App\Policies',
        'App\Queries',
        'App\Repositories',
        'App\Contracts',
        'App\DTOs',
    ]);

arch('actions')
    ->expect('App\Actions')
    ->toHaveMethod('handle');

arch('services')
    ->expect('App\Services')
    ->toHaveMethod('handle');
