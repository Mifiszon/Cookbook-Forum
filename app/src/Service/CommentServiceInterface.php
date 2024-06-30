<?php
/**
* CommentServiceInterface.
 */
namespace App\Service;

use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     * Add.
     *
     * @param Comment $comment Comment
     *
     * @return void Void.
     */
    public function add(Comment $comment): void;

    /**
     * Delete.
     *
     * @param Comment $comment Comment
     *
     * @return void Void.
     */
    public function delete(Comment $comment): void;

    /**
     * Recipe List.
     *
     * @param int $recipeId RecipeId
     * @param int $page     Page
     *
     * @return PaginationInterface PaginatorInterface
     */
    public function getPaginatedCommentsForRecipe(int $recipeId, int $page): PaginationInterface;
}
