<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getAverageRatingForRecipe($recipeId): float|bool|int|string|null
    {
        return $this->createQueryBuilder('r')
            ->select('AVG(r.rating) as averageRating')
            ->andWhere('r.recipe = :recipeId')
            ->setParameter('recipeId', $recipeId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save rating.
     *
     * @param Rating $rating
     */
    public function saveRating(Rating $rating): void
    {
        $this->_em->persist($rating);
        $this->_em->flush();
    }
}
