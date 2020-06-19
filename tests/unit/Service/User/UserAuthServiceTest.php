<?php
declare(strict_types=1);

namespace App\Tests\unit\Service\User;

use App\Entity\User;
use App\Exception\Entity\EntityNotFoundException;
use App\Exception\User\UserAlreadyExistsException;
use App\Service\User\UserAuthService;
use App\Service\User\UserManagerInterface;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;
use stdClass;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class UserAuthServiceTest
 *
 * @package App\Tests\unit\Service\User
 */
class UserAuthServiceTest extends Unit
{
    /**
     * @throws UserAlreadyExistsException
     * @throws Exception
     */
    public function testCanRegisterUser()
    {
        /** @var UserManagerInterface $manager */
        $manager = $this->makeEmpty(
            UserManagerInterface::class,
            [
                'getByUsernameOrEmail' => Expected::once(
                    function () {
                        throw new EntityNotFoundException();
                    }
                ),
                'create'        => Expected::once($this->makeEmpty(User::class)),
                'save'          => Expected::once(),
            ]
        );
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->makeEmpty(
            EventDispatcherInterface::class,
            [
                'dispatch' => Expected::exactly(2, new stdClass()),
            ]
        );

        $service = new UserAuthService($manager, $dispatcher);
        $service->register('username', 'email');
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws Exception
     */
    public function testCanThrowAlreadyRegisteredExceptionIfUserRegistered()
    {
        /** @var UserManagerInterface $manager */
        $manager = $this->makeEmpty(
            UserManagerInterface::class,
            [
                'getByUsernameOrEmail' => Expected::once($this->makeEmpty(User::class)),
            ]
        );
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->makeEmpty(
            EventDispatcherInterface::class,
            [
                'dispatch' => Expected::never(),
            ]
        );

        $service = new UserAuthService($manager, $dispatcher);

        $this->expectException(UserAlreadyExistsException::class);
        $service->register('username', 'email');
    }
}