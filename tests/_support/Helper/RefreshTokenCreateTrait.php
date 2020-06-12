<?php
declare(strict_types=1);

namespace App\Tests\_support\Helper;

use App\Entity\RefreshToken;

/**
 * Trait RefreshTokenCreateTrait
 *
 * @package App\Tests\_support\Helper
 */
trait RefreshTokenCreateTrait
{
    /**
     * @param string      $username
     * @param string|null $value
     *
     * @return RefreshToken
     */
    public function createRefreshToken(string $username, string $value = null): RefreshToken
    {
        $result = new RefreshToken();
        $result->setUsername($username)
               ->setValid(new \DateTime('+5 days'))
               ->setRefreshToken($value === null ? uniqid() : $value);

        return $result;
    }
}