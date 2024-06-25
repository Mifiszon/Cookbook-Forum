<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Picture>
 */
class PictureRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    /**
     * Save entity.
     *
     * @param Picture $picture Picture entity
     * @throws ORMException
     */
    public function save(Picture $picture): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($picture);
        $this->_em->flush();
    }
}
