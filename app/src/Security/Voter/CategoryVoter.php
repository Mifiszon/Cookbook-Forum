<?php

namespace App\Security\Voter;

use App\Entity\Category;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 */
class CategoryVoter extends Voter
{
    private const EDIT = 'EDIT';
    private const VIEW = 'VIEW';
    private const DELETE = 'DELETE';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Category;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface || !$subject instanceof Category) {
            return false;
        }

        return $this->isAdmin($user);
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    private function isAdmin(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
