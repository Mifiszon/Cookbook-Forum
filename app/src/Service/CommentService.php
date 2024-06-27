<?php

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 *
 */
class CommentService implements CommentServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PaginatorInterface $paginator,
        private readonly CommentRepository $commentRepository
    ) {
    }

    /**
     * Dodanie komentarza.
     *
     * @param Comment $comment
     */
    public function add(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Usunięcie komentarza.
     *
     * @param Comment $comment
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

    /**
     * Pobranie stronicowanej listy komentarzy dla przepisu.
     *
     * @param int $recipeId
     * @param int $page
     * @return PaginationInterface
     */
    public function getPaginatedCommentsForRecipe(int $recipeId, int $page): PaginationInterface
    {
        $query = $this->entityManager
            ->getRepository(Comment::class)
            ->createQueryBuilder('c')
            ->andWhere('c.recipe = :recipeId')
            ->setParameter('recipeId', $recipeId)
            ->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
