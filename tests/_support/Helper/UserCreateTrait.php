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
     * @param string $email
     *
     * @return User
     */
    public function createUser(string $username = 'user', string $email = null): User
    {
        if ($email === null) {
            $email = $username . '@example.com';
        }

        $user = new User();
        $user->setUsername($username)
             ->setEmail($email);

        return $user;
    }
}