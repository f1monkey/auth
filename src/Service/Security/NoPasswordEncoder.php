<?php
declare(strict_types=1);

namespace App\Service\Security;

use InvalidArgumentException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class NoPasswordEncoder
 *
 * @package App\Service\Security
 */
class NoPasswordEncoder implements PasswordEncoderInterface
{
    /**
     * @param string      $raw
     * @param string|null $salt
     *
     * @return string The encoded password
     */
    public function encodePassword(string $raw, ?string $salt)
    {
        return '';
    }

    /**
     * @param string      $encoded An encoded password
     * @param string      $raw     A raw password
     * @param string|null $salt    The salt
     *
     * @return bool true if the password is valid, false otherwise
     *
     * @throws InvalidArgumentException If the salt is invalid
     */
    public function isPasswordValid(string $encoded, string $raw, ?string $salt)
    {
        return true;
    }

    /**
     * @param string $encoded
     *
     * @return bool
     */
    public function needsRehash(string $encoded): bool
    {
        return false;
    }
}