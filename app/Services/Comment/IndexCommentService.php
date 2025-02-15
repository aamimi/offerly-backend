<?php

declare(strict_types=1);

namespace App\Services\Comment;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\DTOs\Comment\IndexFilterDTO;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

final readonly class IndexCommentService
{
    /**
     * Create a new service instance.
     */
    public function __construct(private CommentRepositoryInterface $commentRepository) {}

    /**
     * Handle the incoming request.
     *
     * @return Paginator<Comment>
     */
    public function handle(Request $request, string $slug): Paginator
    {
        $filter = new IndexFilterDTO(
            slug: $slug,
            page: $request->integer(key: 'page'),
            perPage: $request->integer(key: 'perPage', default: 5)
        );

        return $this->commentRepository->getCommentsOfProduct($filter);
    }
}
