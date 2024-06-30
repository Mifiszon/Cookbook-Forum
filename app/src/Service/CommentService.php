<?php
/**
* Comment Service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param CommentRepository  $commentRepository  CommentRepository.
     */
    public function __construct(private readonly CommentRepository $commentRepository)
    {
    }

    /**
     * Action add.
     *
     * @param Comment $comment Comment
     *
     * @return void Void.
     */
    public function add(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Action Delete.
     *
     * @param Comment $comment Comment
     *
     * @return void Void.
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

    /**
     * Pobranie stronicowanej listy komentarzy dla przepisu.
     *
     * @param int $recipeId RecipeId.
     * @param int $page     Page.
     *
     * @return PaginationInterface Pagination Interface.
     */
    public function getPaginatedCommentsForRecipe(int $recipeId, int $page): PaginationInterface
    {
        return $this->commentRepository->getPaginatedCommentsForRecipe($recipeId, $page, self::PAGINATOR_ITEMS_PER_PAGE);
    }
}
