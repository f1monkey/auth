<?php
declare(strict_types=1);

namespace App\Factory\AuthCode;

use App\Entity\AuthCode;
use App\Entity\User;

/**
 * Class AuthCodeEntityFactory
 *
 * @package App\Factory\AuthCode
 */
interface AuthCodeEntityFactoryInterface
{
    /**
     * @param User $user
     * @param int  $lifetime
     *
     * @return AuthCode
     */
    public function createForUser(User $user, int $lifetime): AuthCode;
}