<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserAlreadyExistsException;

/**
 * Class UserRegisterService
 *
 * @package App\Service\User
 */
interface UserRegisterServiceInterface
{
    /**
     * @param $username
     * @param $password
     *
     * @return User
     * @throws UserAlreadyExistsException
     */
    public function register(string $username, string $password): User;
}