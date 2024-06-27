<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class IngredientRepository.
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    /**
     * Save entity.
     *
     * @param Ingredient $ingredient Ingredient entity
     *
     */
    public function save(Ingredient $ingredient): void
    {
        $em = $this->getEntityManager();
        $em->persist($ingredient);
        $em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Ingredient $ingredient Ingredient entity
     *
     */
    public function delete(Ingredient $ingredient): void
    {
        $em = $this->getEntityManager();
        $em->remove($ingredient);
        $em->flush();
    }

    /**
     * Find by name.
     *
     * @param string $name Ingredient name
     *
     * @return Ingredient|null Ingredient entity
     */
    public function findOneByName(string $name): ?Ingredient
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * Find by id.
     *
     * @param int $id Ingredient id
     *
     * @return Ingredient|null Ingredient entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Ingredient
    {
        return $this->createQueryBuilder('i')
            ->where('i.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Query all ingredients.
     *
     */
    public function queryAll(): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('i')->getQuery();
    }
}
