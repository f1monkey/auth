<?php
declare(strict_types=1);

namespace App\Factory\Api\V1;

use App\Dto\Api\V1\Response\UserResponse;
use App\Entity\User;

/**
 * Class UserResponseFactory
 *
 * @package App\Factory\Api\V1
 */
interface UserResponseFactoryInterface
{
    /**
     * @param User $user
     *
     * @return UserResponse
     */
    public function createUserResponse(User $user): UserResponse;
}