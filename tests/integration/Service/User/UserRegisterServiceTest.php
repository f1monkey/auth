<?php
declare(strict_types=1);

namespace App\Tests\integration\Service\User;

use App\Exception\User\UserAlreadyExistsException;
use App\Service\User\UserRegisterServiceInterface;
use App\Tests\_support\Mock\MailerMock;
use App\Tests\integration\AbstractIntegrationTestCase;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Class UserRegisterServiceTest
 *
 * @package App\Tests\integration\Service\User
 */
class UserRegisterServiceTest extends AbstractIntegrationTestCase
{
    /**
     * @throws UserAlreadyExistsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testCanRegisterUser()
    {
        $username = 'user';
        $email    = 'user@example.com';
        /** @var UserRegisterServiceInterface $service */
        $service = $this->tester->grabService('test.app.user_register_service');
        $user    = $service->register($username, $email);
        /** @var MailerMock $mailer */
        $mailer = $this->tester->grabService(MailerInterface::class);

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

        static::assertSame(1, $mailer->getEmails()->count());
    }
}