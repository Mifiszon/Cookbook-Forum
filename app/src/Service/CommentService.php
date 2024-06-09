<?php

namespace App\Service;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

class CommentService implements CommentServiceInterface
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function add(Comment $comment): void
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function delete(Comment $comment): void
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}
