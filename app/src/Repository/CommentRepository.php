<?php
/**
 * Comment Repository.
 */

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentRepository.
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry    $registry  registry
     * @param PaginatorInterface $paginator paginator
     */
    public function __construct(ManagerRegistry $registry, private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Save Comment.
     *
     * @param Comment $comment comment
     */
    public function save(Comment $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush();
    }

    /**
     * Delete comment.
     *
     * @param Comment $comment comment
     */
    public function delete(Comment $comment): void
    {
        $this->_em->remove($comment);
        $this->_em->flush();
    }

    /**
     * Pobranie stronicowanej listy komentarzy dla przepisu.
     *
     * @param int $recipeId recipeId
     * @param int $page     page
     * @param int $limit    limit
     *
     * @return PaginationInterface pagination Interface
     */
    public function getPaginatedCommentsForRecipe(int $recipeId, int $page, int $limit): PaginationInterface
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.recipe = :recipeId')
            ->setParameter('recipeId', $recipeId)
            ->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            $limit
        );
    }
}
