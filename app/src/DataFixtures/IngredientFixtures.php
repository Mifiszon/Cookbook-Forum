<?php


namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;

/**
 *
 */
class IngredientFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    private array $ingredients = [
        [
            'name' => 'Makaron spaghetti',
        ],
        [
            'name' => 'Jajka',
        ],
        [
            'name' => 'Ser',
        ],
    ];

    /**
     * Load data.
     */
    public function loadData(): void
    {
        foreach ($this->ingredients as $data) {
            $ingredient = new Ingredient();
            $ingredient->setName($data['name']);

            $this->manager->persist($ingredient);

            $this->addReference('ingredient_' . $data['name'], $ingredient);
        }

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @psalm-return array<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [
            RecipeFixtures::class,
        ];
    }
}
