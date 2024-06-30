<?php
/**
 * Rcipe Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Ingredient;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;

/**
 * Class RecipeFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class RecipeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    private array $sampleRecipes = [
        [
            'title' => 'Spaghetti Carbonara',
            'content' => 'Ugotuj spaghetti. W misce wymieszaj jajka, ser i pieprz. Połącz z ugotowanym spaghetti i pancettą.',
            'ingredients' => ['Makaron spaghetti', 'Jajka', 'Ser'],
        ],
        [
            'title' => 'Kurczak Curry',
            'content' => 'Podsmaż cebulę, czosnek i imbir. Dodaj przyprawy i kurczaka. Gotuj na wolnym ogniu z mlekiem kokosowym aż będzie gotowy.',
            'ingredients' => ['Kurczak', 'Cebula', 'Czosnek', 'Imbir', 'Mleko kokosowe'],
        ],
        [
            'title' => 'Befsztyk Strogonow',
            'content' => 'Smaż wołowinę z cebulą i grzybami. Dodaj bulion, śmietanę i musztardę. Podawaj z makaronem.',
            'ingredients' => ['Wołowina', 'Cebula', 'Grzyby', 'Śmietana', 'Musztarda', 'Makaron'],
        ],
        [
            'title' => 'Warzywna Potrawka',
            'content' => 'Smaż różne warzywa na gorącej patelni z sosem sojowym i czosnkiem. Podawaj z ryżem lub makaronem.',
            'ingredients' => ['Warzywa', 'Sos sojowy', 'Czosnek', 'Ryż', 'Makaron'],
        ],
        [
            'title' => 'Tacos z Rybą',
            'content' => 'Przypraw i grilluj rybę. Podawaj w tortillach z kapustą i skrop sokiem z limonki.',
            'ingredients' => ['Ryba', 'Tortille', 'Kapusta', 'Limonka'],
        ],
        [
            'title' => 'Ciasto Czekoladowe',
            'content' => 'Wymieszaj mąkę, kakao, cukier i jajka. Piecz w piekarniku i polej czekoladowym lukrem.',
            'ingredients' => ['Mąka', 'Kakao', 'Cukier', 'Jajka', 'Czekolada'],
        ],
        [
            'title' => 'Sałatka Cezar',
            'content' => 'Połącz sałatę rzymską, grzanki i sos Cezar. Posyp grillowanym kurczakiem i serem Parmezan.',
            'ingredients' => ['Sałata rzymska', 'Grzanki', 'Sos Cezar', 'Kurczak', 'Ser Parmezan'],
        ],
        [
            'title' => 'Zupa Pomidorowa',
            'content' => 'Gotuj pomidory z cebulą i czosnkiem. Rozdrabniaj na gładką masę i gotuj na wolnym ogniu z śmietaną i bazylią.',
            'ingredients' => ['Pomidory', 'Cebula', 'Czosnek', 'Śmietana', 'Bazyli', 'Chleb'],
        ],
        [
            'title' => 'Żeberka BBQ',
            'content' => 'Marynuj żeberka w sosie BBQ. Gotuj powoli na grillu lub w piekarniku aż będą miękkie.',
            'ingredients' => ['Żeberka', 'Sos BBQ'],
        ],
        [
            'title' => 'Naleśniki',
            'content' => 'Wymieszaj mąkę, mleko, jajka i proszek do pieczenia. Smaż na gorącej patelni i podawaj z syropem.',
            'ingredients' => ['Mąka', 'Mleko', 'Jajka', 'Proszek do pieczenia', 'Syrop'],
        ],
        [
            'title' => 'Lasagne',
            'content' => 'Układaj warstwy makaronu, sera ricotta, sosu mięsnego i sera mozzarella. Piecz w piekarniku aż będzie muskająca.',
            'ingredients' => ['Makaron', 'Ser ricotta', 'Sos mięsny', 'Ser mozzarella'],
        ],
        [
            'title' => 'Grzanka z Serem',
            'content' => 'Umieść ser między dwiema kromkami chleba i smaż na gorącej patelni aż będzie złoty brąz.',
            'ingredients' => ['Ser', 'Chleb'],
        ],
        // Dodaj więcej przepisów według potrzeb
    ];

    /**
     * Load data.
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(count($this->sampleRecipes), 'recipes', function (int $i) {
            $recipe = new Recipe();
            $recipe->setTitle($this->sampleRecipes[$i]['title']);
            $recipe->setContent($this->sampleRecipes[$i]['content']);
            $recipe->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $recipe->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $recipe->setCategory($category);

            /** @var Tag $tags */
            $tags = $this->getRandomReferences('tags', mt_rand(1, 5));
            foreach ($tags as $tag) {
                $recipe->addTag($tag);
            }

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $recipe->setAuthor($author);

            // Dodaj składniki do przepisu
            foreach ($this->sampleRecipes[$i]['ingredients'] as $ingredientName) {
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientName);

                $recipe->addIngredient($ingredient);
            }

            return $recipe;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
