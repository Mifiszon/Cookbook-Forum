<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 *
 */
class RegistrationService implements RegistrationServiceInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;
    private TokenStorageInterface $tokenStorage;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritDoc
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

