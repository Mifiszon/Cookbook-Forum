<?php
/**
 * RegistrationService.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class RegistrationService.
 */
class RegistrationService implements RegistrationServiceInterface
{
    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $passwordHasher password Hasher
     * @param UserRepository              $userRepository user Repository
     * @param TokenStorageInterface       $tokenStorage   token storage
     */
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher, private readonly UserRepository $userRepository, private readonly TokenStorageInterface $tokenStorage)
    {
    }

    /**
     * Register.
     *
     * @param User $user user
     */
    public function register(User $user): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $user->getPlainPassword())
        );

        $this->userRepository->save($user);

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }
}
