<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;

/**
 * Class UserManager
 *
 * @package App\Service\User
 */
interface UserManagerInterface
{
    /**
     * @param string $uuid
     *
     * @return User
     * @throws UserNotFoundException
     */
    public function getById(string $uuid): User;

    /**
     * @param string $username
     * @param string $password
     *
     * @return User
     */
    public function create(string $username, string $password): User;

    /**
     * @param User   $user
     * @param string $password
     */
    public function updatePassword(User $user, string $password): void;

    /**
     * @param User $user
     */
    public function save(User $user): void;
}