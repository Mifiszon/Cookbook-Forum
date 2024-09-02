<?php
/**
 * UserService.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * Constructor.
     *
     * @param UserRepository     $userRepository user repository
     * @param PaginatorInterface $paginator      paginator interface
     */
    public function __construct(private readonly UserRepository $userRepository, private readonly PaginatorInterface $paginator)
    {
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
     * @param User        $user          User entity
     * @param string|null $plainPassword plain password
     */
    public function save(User $user, ?string $plainPassword = null): void
    {
        if (null !== $plainPassword) {
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
     * Promote user.
     *
     * @param User $user User
     *
     * @return void void
     */
    public function promoteUserToAdmin(User $user): void
    {
        $user->promoteToAdmin();
        $this->userRepository->save($user);
    }

    /**
     * Revoke user.
     *
     * @param User $user User
     *
     * @return void void
     */
    public function revokeAdminPrivilegesFromUser(User $user): void
    {
        $user->revokeAdminPrivileges();
        $this->userRepository->save($user);
    }

    /**
     * Last admin.
     *
     * @param User $user User
     *
     * @return bool bool
     *
     * @throws ORMException ORMException
     */
    public function isLastAdmin(User $user): bool
    {
        return 1 === $this->userRepository->countAdmins() && $user->isAdmin();
    }

    /**
     * Change nickname.
     *
     * @param User   $user        User
     * @param string $newNickname newNickname
     *
     * @return void Void
     */
    public function changeNickname(User $user, string $newNickname): void
    {
        $user->setNickname($newNickname);
        $this->userRepository->save($user);
    }

    /**
     * Block user.
     *
     * @param User $user User
     *
     * @return void Void
     */
    public function blockUser(User $user): void
    {
        $user->setIsBlocked(true);
        $this->userRepository->save($user);
    }

    /**
     * Unblock user.
     *
     * @param User $user User
     *
     * @return void Void
     */
    public function unblockUser(User $user): void
    {
        $user->setIsBlocked(false);
        $this->userRepository->save($user);
    }

    /**
     * is User bcloked.
     *
     * @param string $email Email
     *
     * @return bool Bool
     */
    public function isUserBlocked(string $email): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        return $user ? $user->isBlocked() : false;
    }

    /**
     * Find user by email.
     *
     * @param string $email Email
     *
     * @return User|null User
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
