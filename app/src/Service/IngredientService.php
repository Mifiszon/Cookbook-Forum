<?php
/**
 * IngredientService.
 */

namespace App\Service;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class IngredientService.
 */
class IngredientService implements IngredientServiceInterface
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
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param IngredientRepository $ingredientRepository Ingredient repository
     * @param PaginatorInterface   $paginator            Paginator
     */
    public function __construct(private readonly IngredientRepository $ingredientRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->ingredientRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Ingredient $ingredient Ingredient entity
     */
    public function save(Ingredient $ingredient): void
    {
        $this->ingredientRepository->save($ingredient);
    }

    /**
     * Delete entity.
     *
     * @param Ingredient $ingredient Ingredient entity
     */
    public function delete(Ingredient $ingredient): void
    {
        $this->ingredientRepository->delete($ingredient);
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
        return $this->ingredientRepository->findOneByName($name);
    }

    /**
     * Find by id.
     *
     * @param int $id Ingredient id
     *
     * @return Ingredient|null Ingredient entity
     *
     * @throws NonUniqueResultException nonUniqueResultException
     */
    public function findOneById(int $id): ?Ingredient
    {
        return $this->ingredientRepository->findOneById($id);
    }
}
