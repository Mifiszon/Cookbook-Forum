<?php
/**
 * Dane fikstur dla przepisów.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Klasa RecipeFixtures.
 */
class RecipeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Lista przykładowych przepisów.
     *
     * @var array
     */
    private $sampleRecipes = [
        [
            'title' => 'Spaghetti Carbonara',
            'content' => 'Ugotuj spaghetti. W misce wymieszaj jajka, ser i pieprz. Połącz z ugotowanym spaghetti i pancettą.',
        ],
        [
            'title' => 'Kurczak Curry',
            'content' => 'Podsmaż cebulę, czosnek i imbir. Dodaj przyprawy i kurczaka. Gotuj na wolnym ogniu z mlekiem kokosowym aż będzie gotowy.',
        ],
        [
            'title' => 'Befsztyk Strogonow',
            'content' => 'Smaż wołowinę z cebulą i grzybami. Dodaj bulion, śmietanę i musztardę. Podawaj z makaronem.',
        ],
        [
            'title' => 'Warzywna Potrawka',
            'content' => 'Smaż różne warzywa na gorącej patelni z sosem sojowym i czosnkiem. Podawaj z ryżem lub makaronem.',
        ],
        [
            'title' => 'Tacos z Rybą',
            'content' => 'Przypraw i grilluj rybę. Podawaj w tortillach z kapustą i skrop sokiem z limonki.',
        ],
        [
            'title' => 'Ciasto Czekoladowe',
            'content' => 'Wymieszaj mąkę, kakao, cukier i jajka. Piecz w piekarniku i polej czekoladowym lukrem.',
        ],
        [
            'title' => 'Sałatka Cezar',
            'content' => 'Połącz sałatę rzymską, grzanki i sos Cezar. Posyp grillowanym kurczakiem i serem Parmezan.',
        ],
        [
            'title' => 'Zupa Pomidorowa',
            'content' => 'Gotuj pomidory z cebulą i czosnkiem. Rozdrabniaj na gładką masę i gotuj na wolnym ogniu z śmietaną i bazylią.',
        ],
        [
            'title' => 'Żeberka BBQ',
            'content' => 'Marynuj żeberka w sosie BBQ. Gotuj powoli na grillu lub w piekarniku aż będą miękkie.',
        ],
        [
            'title' => 'Naleśniki',
            'content' => 'Wymieszaj mąkę, mleko, jajka i proszek do pieczenia. Smaż na gorącej patelni i podawaj z syropem.',
        ],
        [
            'title' => 'Lasagne',
            'content' => 'Układaj warstwy makaronu, sera ricotta, sosu mięsnego i sera mozzarella. Piecz w piekarniku aż będzie muskająca.',
        ],
        [
            'title' => 'Grzanka z Serem',
            'content' => 'Umieść ser między dwiema kromkami chleba i smaż na gorącej patelni aż będzie złoty brąz.',
        ],
        [
            'title' => 'Risotto z Grzybami',
            'content' => 'Gotuj ryż w bulionie i smażone grzyby. Dodaj ser Parmezan i masło.',
        ],
        [
            'title' => 'Sałatka Grecka',
            'content' => 'Połącz ogórki, pomidory, oliwki i ser feta. Skrop oliwą z oliwek i oregano.',
        ],
        [
            'title' => 'Tiramisu',
            'content' => 'Układaj kawą nasączone paluchy biszkoptowe z mieszanką sera mascarpone. Schłodzić i posypać kakao w proszku.',
        ],
        // Dodaj więcej przepisów według potrzeb
    ];

    /**
     * Wczytaj dane.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
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

            return $recipe;
        });

        $this->manager->flush();
    }

    /**
     * Ta metoda musi zwrócić tablicę klas fikstur,
     * od których zależy klasa implementująca.
     *
     * @return string[] Zależności
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: TagFixtures::class, 2: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
