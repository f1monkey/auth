<?php
declare(strict_types=1);

namespace App\Tests\integration\Service\User;

use App\Service\User\UserSessionManagerInterface;
use App\Tests\integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class UserSessionManagerTest
 *
 * @package App\Tests\integration\Service\User
 */
class UserSessionManagerTest extends AbstractIntegrationTestCase
{
    /**
     * @throws ExpectationFailedException
     */
    public function testCanGetSessionsByUser()
    {
        $username1 = 'user1';
        $username2 = 'user2';

        $user1 = $this->tester->createUser($username1);
        $this->tester->haveInRepository($user1);
        $user2 = $this->tester->createUser($username2);
        $this->tester->haveInRepository($user2);

        $token1 = $this->tester->createRefreshToken($username1);
        $this->tester->haveInRepository($token1);
        $token2 = $this->tester->createRefreshToken($username1);
        $this->tester->haveInRepository($token2);
        $token3 = $this->tester->createRefreshToken($username2);
        $this->tester->haveInRepository($token3);

        /** @var UserSessionManagerInterface $service */
        $service = $this->tester->grabService('test.app.user_session_manager');
        $result1 = $service->getByUser($user1);
        $result2 = $service->getByUser($user2);

        static::assertEqualsCanonicalizing([$token1, $token2], $result1->toArray());
        static::assertEqualsCanonicalizing([$token3], $result2->toArray());
    }

    public function testCanDeleteUserSession()
    {
        $token = $this->tester->createRefreshToken('user');
        $this->tester->haveInRepository($token);

        /** @var UserSessionManagerInterface $service */
        $service = $this->tester->grabService('test.app.user_session_manager');
        $service->delete($token);

        $this->tester->cantSeeInDatabase('refresh_token', ['id' => $token->getId()]);
    }
}