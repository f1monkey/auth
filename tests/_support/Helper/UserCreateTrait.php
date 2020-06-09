<?php
declare(strict_types=1);

namespace App\Tests\_support\Helper;

use App\Entity\User;

/**
 * Trait UserCreateTrait
 *
 * @package App\Tests\_support
 */
trait UserCreateTrait
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return User
     */
    public function createUser(string $username = 'user', string $password = 'password'): User
    {
        $user = new User();
        $user->setUsername($username)
             ->setPlainPassword($password);

        return $user;
    }
}