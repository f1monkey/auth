<?php
declare(strict_types=1);

namespace App\Tests\unit\Factory\Api\V1;

use App\Entity\User;
use App\Factory\Api\V1\UserResponseFactory;
use Codeception\Test\Unit;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class UserResponseFactoryTest
 *
 * @package App\Tests\unit\Factory\Api\V1
 */
class UserResponseFactoryTest extends Unit
{
    /**
     * @dataProvider userDataProvider
     *
     * @param string $username
     * @param string $email
     * @param string $maskedEmail
     *
     * @throws ExpectationFailedException
     */
    public function testCanCreateUserResponse(string $username, string $email, string $maskedEmail)
    {
        $user = new User();
        $user->setUsername($username)
             ->setEmail($email);

        $factory = new UserResponseFactory();
        $result = $factory->createUserResponse($user);

        static::assertSame($username, $result->getUsername());
        static::assertSame($maskedEmail, $result->getEmail());
    }

    public function userDataProvider(): array
    {
        return [
            [
                'user',
                'user@example.com',
                'u**********@example.com',
            ],
            [
                'user',
                'u@example.com',
                'u**********@example.com',
            ],
            [
                'user',
                'us@example.com',
                'u**********@example.com',
            ],
        ];
    }
}