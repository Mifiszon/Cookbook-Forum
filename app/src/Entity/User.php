<?php
/**
 * User entity.
 */

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'email_idx', columns: ['email'])]
#[UniqueEntity(fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Email.
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * Roles.
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Password.
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private ?string $password = null;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     */
    #[Assert\NotBlank(groups: ['registration'])]
    #[Assert\Length(min: 6, groups: ['registration'])]
    private ?string $plainPassword = null;

    /**
     * Avatar.
     */
    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Avatar $avatar = null;

    /**
     * Nickname.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $nickname = null;

    /**
     * isBlocked.
     */
    #[ORM\Column(type: 'boolean')]
    public bool $isBlocked = false;

    /**
     * Ratings.
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Rating::class)]
    private Collection $ratings;

    /**
     * Constructor for ratings.
     */
    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for isBlocked.
     *
     * @return bool Type
     */
    public function getIsBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * Setter for isBlocked.
     *
     * @param bool $isBlocked isBlocked
     *
     * @return $this This
     */
    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    /**
     * Getter for email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email Email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @return string User identifier
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter for username.
     *
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     *
     * @return string Username
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter for roles.
     *
     * @return array<int, string> Roles
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRole::ROLE_USER->value;

        return array_unique($roles);
    }

    /**
     * Setter for roles.
     *
     * @param array<int, string> $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Getter for password.
     *
     * @return string|null Password
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter for password.
     *
     * @param string $password User password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @return string|null Type
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Getter for plainPassword.
     *
     * @return string|null Plain password
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Setter for plainPassword.
     *
     * @param string|null $plainPassword Plain password
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Removes sensitive information from the token.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * Getter for avatar.
     *
     * @return Avatar|null Avatar
     */
    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    /**
     * Setter for avatar.
     *
     * @param Avatar $avatar Avatar
     *
     * @return $this This
     */
    public function setAvatar(Avatar $avatar): static
    {
        // set the owning side of the relation if necessary
        if ($avatar->getUser() !== $this) {
            $avatar->setUser($this);
        }

        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Promote user to admin.
     */
    public function promoteToAdmin(): void
    {
        $roles = $this->getRoles();
        $roles[] = UserRole::ROLE_ADMIN->value;
        $this->setRoles($roles);
    }

    /**
     * Revoke admin privileges from user.
     */
    public function revokeAdminPrivileges(): void
    {
        $roles = $this->getRoles();
        $roles = array_diff($roles, [UserRole::ROLE_ADMIN->value]);
        $this->setRoles($roles);
    }

    /**
     * Getter for nickname.
     *
     * @return string|null Type
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * Setter for nickname.
     *
     * @param string $nickname Nickname
     *
     * @return $this This
     */
    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Check if user is an admin.
     *
     * @return bool Type
     */
    public function isAdmin(): bool
    {
        return in_array(UserRole::ROLE_ADMIN->value, $this->getRoles(), true);
    }

    /**
     * Getter for Ratings.
     *
     * @return Collection<int, Rating> Ratings
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    /**
     * Adds ratings.
     *
     * @param Rating $rating Rating
     *
     * @return $this This
     */
    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setUser($this);
        }

        return $this;
    }

    /**
     * Removes rating.
     *
     * @param Rating $rating Rating
     *
     * @return $this This
     */
    public function removeRating(Rating $rating): static
    {
        // set the owning side to null (unless already changed)
        if ($this->ratings->removeElement($rating) && $rating->getUser() === $this) {
            $rating->setUser(null);
        }

        return $this;
    }
}
