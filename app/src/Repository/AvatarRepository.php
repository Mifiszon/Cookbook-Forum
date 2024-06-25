<?php

namespace App\Repository;

use App\Entity\Avatar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avatar>
 */
class AvatarRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avatar::class);
    }

    /**
     * Save entity.
     *
     * @param Avatar $avatar Avatar entity
     * @throws ORMException
     */
    public function save(Avatar $avatar): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($avatar);
        $this->_em->flush();
    }
}
