<?php
/**
* IngredientServiceInterface.
 */

namespace App\Service;

use App\Entity\Ingredient;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface IngredientServiceInterface.
 */
interface IngredientServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<PaginationInterface<Ingredient>> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Ingredient $ingredient Ingredient entity
     */
    public function save(Ingredient $ingredient): void;

    /**
     * Delete entity.
     *
     * @param Ingredient $ingredient Ingredient entity
     */
    public function delete(Ingredient $ingredient): void;

    /**
     * Find by name.
     *
     * @param string $name Ingredient name
     *
     * @return Ingredient|null Ingredient entity
     */
    public function findOneByName(string $name): ?Ingredient;

    /**
     * Find by id.
     *
     * @param int $id Ingredient id
     *
     * @return Ingredient|null Ingredient entity
     */
    public function findOneById(int $id): ?Ingredient;
}
