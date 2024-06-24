<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RecipeRepository $recipeRepository,
        private readonly PaginatorInterface $paginator,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            10
        );
    }

    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function delete(User $user): void
    {
        $recipes = $this->recipeRepository->findByUser($user);
        foreach ($recipes as $recipe) {
            $this->recipeRepository->delete($recipe);
        }

        $this->entityManager->flush();
        $this->userRepository->delete($user);
    }

    public function promoteUserToAdmin(User $user): void
    {
        $user->promoteToAdmin();
        $this->entityManager->flush();
    }

    public function revokeAdminPrivilegesFromUser(User $user): void
    {
        $user->revokeAdminPrivileges();
        $this->entityManager->flush();
    }

    public function isLastAdmin(User $user): bool
    {
        return $this->userRepository->countAdmins() === 1 && $user->isAdmin();
    }

    public function changeNickname(User $user, string $newNickname): void
    {
        $user->setNickname($newNickname);
        $this->entityManager->flush();
    }

    public function blockUser(User $user): void
    {
        $user->setIsBlocked(true);
        $this->entityManager->flush();
    }

    public function unblockUser(User $user): void
    {
        $user->setIsBlocked(false);
        $this->entityManager->flush();
    }

    public function isUserBlocked(string $email): bool
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        return $user->isBlocked;
    }
}
