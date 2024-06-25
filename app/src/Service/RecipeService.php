<?php
/**
 * Recipe service.
 */

namespace App\Service;

use App\Dto\RecipeListFiltersDto;
use App\Dto\RecipeListInputFiltersDto;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use Doctrine\ORM\Exception\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RecipeService.
 */
class RecipeService implements RecipeServiceInterface
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
     * @param CategoryServiceInterface  $categoryService Category service
     * @param PaginatorInterface        $paginator       Paginator
     * @param TagServiceInterface       $tagService      Tag service
     * @param RecipeRepository         $recipeRepository  Recipe repository
     */
    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
        private readonly PaginatorInterface $paginator,
        private readonly TagServiceInterface $tagService,
        private readonly RecipeRepository $recipeRepository
    ) {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     * @param User|null $author Recipes author
     * @param RecipeListInputFiltersDto $filters Filters
     *
     * @return PaginationInterface<SlidingPagination> Paginated list
     */
    public function getPaginatedList(int $page, ?User $author, RecipeListInputFiltersDto $filters): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        if ($author) {
            $query = $this->recipeRepository->queryByAuthor($author, $filters);
        } else {
            $query = $this->recipeRepository->queryAll($filters);
        }

        return $this->paginator->paginate(
            $query,
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     * @throws ORMException
     */
    public function save(Recipe $recipe): void
    {
        $this->recipeRepository->save($recipe);
    }

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     * @throws ORMException
     */
    public function delete(Recipe $recipe): void
    {
        $this->recipeRepository->delete($recipe);
    }

    /**
     * Prepare filters for the recipes list.
     *
     * @param RecipeListInputFiltersDto $filters Raw filters from request
     *
     * @return RecipeListFiltersDto Result filters
     */
    private function prepareFilters(RecipeListInputFiltersDto $filters): RecipeListFiltersDto
    {
        return new RecipeListFiltersDto(
            null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null,
            null !== $filters->tagId ? $this->tagService->findOneById($filters->tagId) : null,
        );
    }

}
