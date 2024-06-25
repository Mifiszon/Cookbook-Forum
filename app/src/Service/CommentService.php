<?php

namespace App\Service;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 *
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PaginatorInterface $paginator
    ) {
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function add(Comment $comment): void
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function delete(Comment $comment): void
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    /**
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
