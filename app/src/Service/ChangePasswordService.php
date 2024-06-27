<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

/**
 *
 */
class ChangePasswordService implements ChangePasswordServiceInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
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
            throw new InvalidCsrfTokenException('Invalid current password.');
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
        $this->userRepository->save($user);
    }
}
