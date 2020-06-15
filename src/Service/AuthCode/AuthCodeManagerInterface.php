<?php
declare(strict_types=1);

namespace App\Service\AuthCode;

use App\Entity\AuthCode;
use App\Entity\User;
use App\Exception\AuthCode\TooManyAuthCodesException;
use App\Exception\Entity\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Interface AuthCodeManagerInterface
 *
 * @package App\Service\AuthCode
 */
interface AuthCodeManagerInterface
{
    /**
     * @param User $user
     *
     * @return AuthCode
     * @throws TooManyAuthCodesException
     */
    public function createForUser(User $user): AuthCode;

    /**
     * @param User   $user
     * @param string $token
     *
     * @return AuthCode
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function get(User $user, string $token): AuthCode;
}