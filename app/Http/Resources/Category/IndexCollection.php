<?php

declare(strict_types=1);

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class IndexCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = IndexResource::class;
}
