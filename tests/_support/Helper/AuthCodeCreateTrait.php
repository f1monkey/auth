<?php
declare(strict_types=1);

namespace App\Tests\_support\Helper;

use App\Entity\AuthCode;
use App\Entity\User;

/**
 * Trait AuthCodeCreateTrait
 *
 * @package App\Tests\_support\Helper
 */
trait AuthCodeCreateTrait
{
    /**
     * @param User        $user
     * @param string|null $authCode
     *
     * @return AuthCode
     */
    public function createAuthCode(User $user, string $authCode = null): AuthCode
    {
        $result = new AuthCode();
        $result->setParentUser($user)
               ->setInvalidateAt(new \DateTimeImmutable('+1 day'))
               ->setCode($authCode ?? 'qwerty');

        return $result;
    }
}