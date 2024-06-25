<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * @param UserRepository $userRepository
     * @param RecipeRepository $recipeRepository
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RecipeRepository $recipeRepository,
        private readonly PaginatorInterface $paginator,
        private readonly EntityManagerInterface $entityManager
    ) {
    }
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            10
        );
    }
    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }
    /**
     * Delete entity.
     *
     * @param User $user User entity
     * @throws ORMException
     */
    public function delete(User $user): void
    {
        $recipes = $this->recipeRepository->findByUser($user);
        foreach ($recipes as $recipe) {
            $this->recipeRepository->delete($recipe);
        }

        $this->entityManager->flush();
        $this->userRepository->delete($user);
    }
    /**
     * @param User $user
     * @return void
     */
    public function promoteUserToAdmin(User $user): void
    {
        $user->promoteToAdmin();
        $this->entityManager->flush();
    }
    /**
     * @param User $user
     * @return void
     */
    public function revokeAdminPrivilegesFromUser(User $user): void
    {
        $user->revokeAdminPrivileges();
        $this->entityManager->flush();
    }
    /**
     * @param User $user
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function isLastAdmin(User $user): bool
    {
        return $this->userRepository->countAdmins() === 1 && $user->isAdmin();
    }
    /**
     * @param User $user
     * @param string $newNickname
     * @return void
     */
    public function changeNickname(User $user, string $newNickname): void
    {
        $user->setNickname($newNickname);
        $this->entityManager->flush();
    }
    /**
     * @param User $user
     * @return void
     */
    public function blockUser(User $user): void
    {
        $user->setIsBlocked(true);
        $this->entityManager->flush();
    }
    /**
     * @param User $user
     * @return void
     */
    public function unblockUser(User $user): void
    {
        $user->setIsBlocked(false);
        $this->entityManager->flush();
    }
    /**
     * @param string $email
     * @return bool
     */
    public function isUserBlocked(string $email): bool
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        return $user->isBlocked;
    }
    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
