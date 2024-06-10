<?php

namespace App\Service;

use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CommentServiceInterface
{
    public function add(Comment $comment): void;

    public function delete(Comment $comment): void;

    public function getPaginatedCommentsForRecipe(int $recipeId, int $page): PaginationInterface;

}
