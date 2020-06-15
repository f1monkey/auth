<?php
declare(strict_types=1);

namespace App\Tests\integration\Service\User;

use App\Exception\Entity\EntityNotFoundException;
use App\Service\User\UserManagerInterface;
use App\Tests\integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class UserManagerTest
 *
 * @package App\Tests\integration\Service\User
 */
class UserManagerTest extends AbstractIntegrationTestCase
{
    /**
     *
     */
    public function testCanCreateUser()
    {
        $username = 'user';
        $email = 'user@example.com';
        /** @var UserManagerInterface $manager */
        $manager = $this->tester->grabService('test.app.user_manager');
        $user = $manager->create($username, $email);
        $manager->save($user);

        $this->tester->seeInDatabase('user', ['username' => $username]);
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExpectationFailedException
     */
    public function testCanGetUserById()
    {
        $user = $this->tester->createUser();
        $this->tester->haveInRepository($user);

        /** @var UserManagerInterface $manager */
        $manager = $this->tester->grabService('test.app.user_manager');
        $result = $manager->getById($user->getId());

        $this->assertSame($user, $result);
    }
}