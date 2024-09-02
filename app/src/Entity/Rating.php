<?php
/**
 * Rating Entity.
 */

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rating.
 */
#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\Table(name: 'ratings')]
class Rating
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Rating.
     */
    #[ORM\Column]
    private ?int $rating = null;

    /**
     * User.
     */
    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * Recipe.
     */
    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

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
     * Getter for rating.
     *
     * @return int|null Type
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * Setter for rating.
     *
     * @param int $rating rating
     *
     * @return $this this
     */
    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Getter for user.
     *
     * @return User|null User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for user.
     *
     * @param User|null $user user
     *
     * @return $this this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Getter for recipe.
     *
     * @return Recipe|null Recipe
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Setter for recipe.
     *
     * @param Recipe|null $recipe recipe
     *
     * @return $this this
     */
    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
