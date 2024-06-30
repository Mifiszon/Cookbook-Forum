<?php
/**
* Recipe Service.
 */

namespace App\Service;

use App\Dto\RecipeListFiltersDto;
use App\Dto\RecipeListInputFiltersDto;
use App\Entity\Rating;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RatingRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RecipeService.
 *
 * This service handles operations related to recipes,
 * including pagination, saving, deleting, ratings management,
 * and searching recipes by ingredients.
 */
class RecipeService implements RecipeServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService  Category service
     * @param PaginatorInterface       $paginator        Paginator
     * @param TagServiceInterface      $tagService       Tag service
     * @param RatingRepository         $ratingRepository Rating repository
     * @param RecipeRepository         $recipeRepository Recipe repository
     */
    public function __construct(private readonly CategoryServiceInterface $categoryService, private readonly PaginatorInterface $paginator, private readonly TagServiceInterface $tagService, private readonly RatingRepository $ratingRepository, private readonly RecipeRepository $recipeRepository)
    {
    }

    /**
     * Get paginated list of recipes.
     *
     * @param int                       $page    Page number
     * @param User|null                 $author  Recipes author
     * @param RecipeListInputFiltersDto $filters Filters
     *
     * @return PaginationInterface<SlidingPagination> Paginated list of recipes
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
     * Save a recipe entity.
     *
     * @param Recipe $recipe Recipe entity to save
     *
     * @throws ORMException ORMException.
     */
    public function save(Recipe $recipe): void
    {
        $this->recipeRepository->save($recipe);
    }

    /**
     * Delete a recipe entity.
     *
     * @param Recipe $recipe Recipe entity to delete
     *
     * @throws ORMException ORMException.
     */
    public function delete(Recipe $recipe): void
    {
        $this->recipeRepository->delete($recipe);
    }

    /**
     * Add a rating to a recipe.
     *
     * @param Rating $rating Rating entity to add
     */
    public function addRating(Rating $rating): void
    {
        $this->ratingRepository->saveRating($rating);
    }

    /**
     * Calculate average ratings for an array of recipes.
     *
     * @param array $recipes Array of Recipe entities
     *
     * @return array Array of average ratings indexed by recipe ID
     *
     * @throws NoResultException NoResultException.
     * @throws NonUniqueResultException NonUniqueResultException.
     */
    public function getAverageRatings(array $recipes): array
    {
        $averageRatings = [];
        foreach ($recipes as $recipe) {
            $averageRatings[$recipe->getId()] = $this->ratingRepository->getAverageRatingForRecipe($recipe);
        }

        return $averageRatings;
    }

    /**
     * Get ratings for a specific recipe.
     *
     * @param Recipe $recipe Recipe entity
     *
     * @return array Array of Rating entities
     */
    public function getRatingsForRecipe(Recipe $recipe): array
    {
        return $this->ratingRepository->findBy(['recipe' => $recipe]);
    }

    /**
     * Find recipes by a list of ingredients.
     *
     * @param array $ingredients Array of ingredient names
     *
     * @return array Array of Recipe entities
     */
    public function findRecipesByIngredients(array $ingredients): array
    {
        return $this->recipeRepository->findByIngredients($ingredients);
    }

    /**
     * Prepare filters for the recipes list.
     *
     * @param RecipeListInputFiltersDto $filters Raw filters from request
     *
     * @return RecipeListFiltersDto Result filters for querying recipes
     */
    private function prepareFilters(RecipeListInputFiltersDto $filters): RecipeListFiltersDto
    {
        return new RecipeListFiltersDto(
            null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null,
            null !== $filters->tagId ? $this->tagService->findOneById($filters->tagId) : null,
        );
    }
}
