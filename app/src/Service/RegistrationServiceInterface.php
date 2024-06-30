<?php
/**
 * RegistrationServiceInterface.
 */

namespace App\Service;

use App\Entity\User;

/**
 * Class RegistrationServiceInterface.
 */
interface RegistrationServiceInterface
{
    /**
     * Register a new user.
     *
     * @param User $user The user entity
     */
    public function register(User $user): void;
}
