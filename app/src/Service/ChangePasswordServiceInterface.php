<?php

namespace App\Service;

use App\Entity\User;

/**
 *
 */
interface ChangePasswordServiceInterface
{
    /**
     * Change the user's password.
     *
     * @param User   $user            The user entity
     * @param string $currentPassword The current password
     * @param string $newPassword     The new password
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): void;
}
