<?php
/**
 * Ingredient Entity.
 */

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ingredient.
 */
#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\Table(name: 'inrgedients')]
class Ingredient implements \Stringable
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Recipe.
     */
    #[ORM\ManyToMany(targetEntity: Recipe::class, inversedBy: 'ingredients')]
    private Collection $recipe;

    /**
     * Name.
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * Contructor for recipes.
     */
    public function __construct()
    {
        $this->recipe = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return int|null Type
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for recipe.
     *
     * @return Collection<int, Recipe>
     */
    public function getRecipe(): Collection
    {
        return $this->recipe;
    }

    /**
     * Adds recipes.
     *
     * @param Recipe $recipe recipe
     *
     * @return $this this
     */
    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipe->contains($recipe)) {
            $this->recipe->add($recipe);
        }

        return $this;
    }

    /**
     * Removes recipes.
     *
     * @param Recipe $recipe recipe
     *
     * @return $this this
     */
    public function removeRecipe(Recipe $recipe): static
    {
        $this->recipe->removeElement($recipe);

        return $this;
    }

    /**
     * Getter for name.
     *
     * @return string|null Type
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string $name name
     *
     * @return $this this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Converts to string.
     *
     * @return string Type
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }
}
