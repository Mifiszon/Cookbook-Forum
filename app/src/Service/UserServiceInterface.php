<?php

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void;

    /**
     * Delete entity.
     *
     * @param User $user User entity
     */
    public function delete(User $user): void;

    /**
     * @param User $user
     *
     * @return void
     */
    public function promoteUserToAdmin(User $user): void;

    /**
     * @param User $user
     *
     * @return void
     */
    public function revokeAdminPrivilegesFromUser(User $user): void;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isLastAdmin(User $user): bool;

    /**
     * @param User   $user
     * @param string $newNickname
     *
     * @return void
     */
    public function changeNickname(User $user, string $newNickname): void;

    /**
     * @param User $user
     *
     * @return void
     */
    public function blockUser(User $user): void;

    /**
     * @param User $user
     *
     * @return void
     */
    public function unblockUser(User $user): void;

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isUserBlocked(string $email): bool;

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User;
}
