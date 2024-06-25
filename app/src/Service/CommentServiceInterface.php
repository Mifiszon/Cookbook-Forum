<?php

namespace App\Service;

use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 *
 */
interface CommentServiceInterface
{
    /**
     * @param Comment $comment
     * @return void
     */
    public function add(Comment $comment): void;

    /**
     * @param Comment $comment
     * @return void
     */
    public function delete(Comment $comment): void;

    /**
     * @param int $recipeId
     * @param int $page
     * @return PaginationInterface
     */
    public function getPaginatedCommentsForRecipe(int $recipeId, int $page): PaginationInterface;
}
