<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 *
 */
class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;
    private PaginatorInterface $paginator;

    /**
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
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
    public function save(User $user, string $plainPassword = null): void
    {
        if ($plainPassword !== null) {
            $user->setPassword($plainPassword);
        }
        $this->userRepository->save($user);
    }

    /**
     * Delete entity.
     *
     * @param User $user User entity
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function promoteUserToAdmin(User $user): void
    {
        $user->promoteToAdmin();
        $this->userRepository->save($user);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function revokeAdminPrivilegesFromUser(User $user): void
    {
        $user->revokeAdminPrivileges();
        $this->userRepository->save($user);
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws ORMException
     */
    public function isLastAdmin(User $user): bool
    {
        return $this->userRepository->countAdmins() === 1 && $user->isAdmin();
    }

    /**
     * @param User $user
     * @param string $newNickname
     *
     * @return void
     */
    public function changeNickname(User $user, string $newNickname): void
    {
        $user->setNickname($newNickname);
        $this->userRepository->save($user);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function blockUser(User $user): void
    {
        $user->setIsBlocked(true);
        $this->userRepository->save($user);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function unblockUser(User $user): void
    {
        $user->setIsBlocked(false);
        $this->userRepository->save($user);
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isUserBlocked(string $email): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        return $user ? $user->isBlocked() : false;
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
