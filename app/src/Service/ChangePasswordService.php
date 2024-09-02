<?php
/**
 * ChangePasswordService.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

/**
 * Class ChangePasswordService.
 */
class ChangePasswordService implements ChangePasswordServiceInterface
{
    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $passwordHasher passwordHasher
     * @param UserRepository              $userRepository userRepository
     */
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher, private readonly UserRepository $userRepository)
    {
    }

    /**
     * Change the user's password.
     *
     * @param User   $user            The user entity
     * @param string $currentPassword The current password
     * @param string $newPassword     The new password
     *
     * @throws InvalidCsrfTokenException if the current password is invalid
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!$this->passwordHasher->isPasswordValid($user, $currentPassword)) {
            throw new InvalidCsrfTokenException('Invalid current password');
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
        $this->userRepository->save($user);
    }
}
