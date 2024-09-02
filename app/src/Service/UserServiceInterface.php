<?php
/**
 * UserServiceInterface.
 */

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class UserServiceInterface.
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
     * @param User        $user          User entity
     * @param string|null $plainPassword plain Password
     */
    public function save(User $user, ?string $plainPassword = null): void;

    /**
     * Delete entity.
     *
     * @param User $user User entity
     */
    public function delete(User $user): void;

    /**
     * Promote user.
     *
     * @param User $user user
     *
     * @return void void
     */
    public function promoteUserToAdmin(User $user): void;

    /**
     * Revoke user.
     *
     * @param User $user user
     *
     * @return void void
     */
    public function revokeAdminPrivilegesFromUser(User $user): void;

    /**
     * Is last admin.
     *
     * @param User $user user
     *
     * @return bool bool
     */
    public function isLastAdmin(User $user): bool;

    /**
     * Change Nickname.
     *
     * @param User   $user        user
     * @param string $newNickname newNickname
     *
     * @return void void
     */
    public function changeNickname(User $user, string $newNickname): void;

    /**
     * Block user.
     *
     * @param User $user user
     *
     * @return void void
     */
    public function blockUser(User $user): void;

    /**
     * Unblock user.
     *
     * @param User $user user
     *
     * @return void void
     */
    public function unblockUser(User $user): void;

    /**
     * is user blocked.
     *
     * @param string $email email
     *
     * @return bool bool
     */
    public function isUserBlocked(string $email): bool;

    /**
     * Find user by email.
     *
     * @param string $email email
     *
     * @return User|null user
     */
    public function findUserByEmail(string $email): ?User;
}
