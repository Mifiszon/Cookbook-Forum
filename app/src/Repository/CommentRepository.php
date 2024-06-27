<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Zapisanie komentarza.
     *
     * @param Comment $comment
     */
    public function save(Comment $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush();
    }

    /**
     * Usunięcie komentarza.
     *
     * @param Comment $comment
     */
    public function delete(Comment $comment): void
    {
        $this->_em->remove($comment);
        $this->_em->flush();
    }
}
