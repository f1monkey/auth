<?php
declare(strict_types=1);

namespace App\Tests\integration\Service\User;

use App\Exception\User\UserAlreadyExistsException;
use App\Service\User\UserRegisterServiceInterface;
use App\Tests\integration\AbstractIntegrationTestCase;

/**
 * Class UserRegisterServiceTest
 *
 * @package App\Tests\integration\Service\User
 */
class UserRegisterServiceTest extends AbstractIntegrationTestCase
{
    /**
     * @throws UserAlreadyExistsException
     */
    public function testCanRegisterUser()
    {
        $username = 'user';
        $email    = 'user@example.com';
        /** @var UserRegisterServiceInterface $service */
        $service = $this->tester->grabService('test.app.user_register_service');
        $user    = $service->register($username, $email);

        $this->tester->canSeeInDatabase(
            'user',
            [
                'username' => $username,
                'email'    => $email,
            ]
        );
        $this->tester->canSeeInDatabase(
            'auth_code',
            [
                'parent_user_id' => $user->getId(),
            ]
        );
    }
}