<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return QueryBuilder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('user')
            ->orderBy('user.email', 'ASC');
    }

    /**
     * Save entity.
     *
     * @param User $user User entity
     *
     */
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param User $user User entity
     *
     */
    public function delete(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    /**
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countAdmins(): int
    {
        return (int) $this->createQueryBuilder('user')
            ->select('COUNT(user.id)')
            ->where('user.roles LIKE :role')
            ->setParameter('role', '%"ROLE_ADMIN"%')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
