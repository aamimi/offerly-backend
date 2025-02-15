<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Comments;

use App\Http\Resources\v1\Comment\IndexCollection;
use App\Services\Comment\IndexCommentService;
use Illuminate\Http\Request;

final readonly class IndexController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private IndexCommentService $commentService) {}

    /**
     * Display a listing of the product comments.
     */
    public function __invoke(Request $request, string $slug): IndexCollection
    {
        return new IndexCollection($this->commentService->handle($request, $slug));
    }
}
