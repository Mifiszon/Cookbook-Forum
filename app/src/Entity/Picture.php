<?php
/**
 * Picture entity.
 */

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Picture.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ORM\Table(name: 'pictures')]
#[ORM\UniqueConstraint(name: 'uq_pictures_filename', columns: ['filename'])]
#[UniqueEntity(fields: ['filename'])]
class Picture
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Recipe.
     *
     * @var Recipe|null
     */
    #[ORM\OneToOne(
        inversedBy: 'picture', targetEntity: Recipe::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Type(Recipe::class)]
    private ?Recipe $recipe;

    /**
     * Filename.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 191)]
    #[Assert\Type('string')]
    private ?string $filename;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @param Recipe|null $recipe Recipe
     */
    public function setRecipe(?Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }

    /**
     * Getter for filename.
     *
     * @return string|null Filename
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Setter for filename.
     *
     * @param string|null $filename Filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }
}
