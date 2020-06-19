<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\Entity\EntityNotFoundException;

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
     * @throws EntityNotFoundException
     */
    public function getById(string $uuid): User;

    /**
     * @param string $username
     *
     * @return User
     * @throws EntityNotFoundException
     */
    public function getByUsernameOrEmail(string $username): User;

    /**
     * @param string $username
     * @param string $email
     *
     * @return User
     */
    public function create(string $username, string $email): User;

    /**
     * @param User $user
     */
    public function save(User $user): void;
}