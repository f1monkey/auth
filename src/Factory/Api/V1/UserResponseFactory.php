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
class UserResponseFactory implements UserResponseFactoryInterface
{
    /**
     * @param User $user
     *
     * @return UserResponse
     */
    public function createUserResponse(User $user): UserResponse
    {
        return (new UserResponse())->setUsername($user->getUsername())
                                   ->setEmail($user->getEmail());
    }
}