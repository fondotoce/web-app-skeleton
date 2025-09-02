<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @param User $user
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (null === $user->getBannedUntil()) {
            return;
        }

        $now = new \DateTime();

        if ($now <  $user->getBannedUntil()) {
            throw new AccessDeniedHttpException('User is banned');
        }
    }

    /**
     * @param User $user
     */
    public function checkPostAuth(UserInterface $user): void
    {
        // TODO: Implement checkPostAuth() method.
    }
}
