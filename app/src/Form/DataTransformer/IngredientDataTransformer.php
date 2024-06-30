<?php
/**
 * Ingredients Data Transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Ingredient;
use App\Service\IngredientServiceInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class IngredientsDataTransformer.
 *
 * @implements DataTransformerInterface<mixed, mixed>
 */
class IngredientDataTransformer implements DataTransformerInterface
{
    /**
     * Constructor.
     *
     * @param IngredientServiceInterface $ingredientService Ingredient service
     */
    public function __construct(private readonly IngredientServiceInterface $ingredientService)
    {
    }

    /**
     * Transform array of ingredients to string of ingredient names.
     *
     * @param Collection<int, Ingredient> $value Ingredients entity collection
     *
     * @return string Result
     */
    public function transform($value): string
    {
        if ($value->isEmpty()) {
            return '';
        }

        $ingredientNames = [];

        foreach ($value as $ingredient) {
            $ingredientNames[] = $ingredient->getName();
        }

        return implode("\n", $ingredientNames);
    }

    /**
     * Transform string of ingredient names into array of Ingredient entities.
     *
     * @param string $value String of ingredient names
     *
     * @return array<int, Ingredient> Result
     */
    public function reverseTransform($value): array
    {
        $ingredientNames = explode("\n", $value);

        $ingredients = [];

        foreach ($ingredientNames as $ingredientName) {
            if ('' !== trim($ingredientName)) {
                $ingredient = $this->ingredientService->findOneByName(strtolower($ingredientName));
                if (null === $ingredient) {
                    $ingredient = new Ingredient();
                    $ingredient->setName($ingredientName);

                    $this->ingredientService->save($ingredient);
                }
                $ingredients[] = $ingredient;
            }
        }

        return $ingredients;
    }
}
