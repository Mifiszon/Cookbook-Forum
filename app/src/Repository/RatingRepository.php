<?php
/**
* Rating Repository.
 */

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class RatingRepository.
 */
class RatingRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    /**
     * Get average rating for recipe.
     *
     * @param int $recipeId recipeId.
     *
     * @return float|bool|int|string|null Type.
     *
     * @throws NoResultException NoResultException.
     * @throws NonUniqueResultException NonUniqueResultException.
     */
    public function getAverageRatingForRecipe(int $recipeId): float|bool|int|string|null
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
