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

    public function promoteUserToAdmin(User $user): void;

    public function revokeAdminPrivilegesFromUser(User $user): void;

    public function isLastAdmin(User $user): bool;

    public function changeNickname(User $user, string $newNickname): void;

    public function blockUser(User $user): void;

    public function unblockUser(User $user): void;

    public function isUserBlocked(string $email): bool;


}