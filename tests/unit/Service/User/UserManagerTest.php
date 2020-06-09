<?php
declare(strict_types=1);

namespace App\Tests\unit\Service\User;

use App\Entity\User;
use App\Exception\Entity\EntityNotFoundException;
use App\Repository\UserRepository;
use App\Service\User\UserManager;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class UserManagerTest
 *
 * @package App\Tests\unit\Service\User
 */
class UserManagerTest extends Unit
{
    /**
     * @throws ExpectationFailedException
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function testCanGetUserById()
    {
        $expected = new User();
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var UserRepository $repository */
        $repository = $this->makeEmpty(
            UserRepository::class,
            [
                'findById' => Expected::once($expected),
            ]
        );

        $service = new UserManager($em, $repository);
        $result = $service->getById('uuid');

        static::assertSame($expected, $result);
    }

    /**
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function testCannotGetNonExistingUserById()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var UserRepository $repository */
        $repository = $this->makeEmpty(
            UserRepository::class,
            [
                'findById' => Expected::once(null),
            ]
        );

        $service = new UserManager($em, $repository);

        $this->expectException(EntityNotFoundException::class);
        $service->getById('uuid');
    }

    /**
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testCanCreateUser()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var UserRepository $repository */
        $repository = $this->makeEmpty(UserRepository::class);
        $service = new UserManager($em, $repository);

        $username = 'user';
        $password = 'password';
        $user = $service->create($username, $password);

        static::assertSame($username, $user->getUsername());
        static::assertSame($password, $user->getPlainPassword());
    }

    /**
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testCanUpdateUserPassword()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(
            EntityManagerInterface::class,
            [
                'persist' => Expected::once(),
                'flush'   => Expected::once(),
            ]
        );
        /** @var UserRepository $repository */
        $repository = $this->makeEmpty(UserRepository::class);
        $service = new UserManager($em, $repository);

        $password = 'password';
        $newPassword = 'newPassword';
        $user = new User();
        $user->setPassword($password);
        $service->updatePassword($user, $newPassword);

        static::assertSame($newPassword, $user->getPlainPassword());
    }

    /**
     * @throws Exception
     */
    public function testCanSaveUser()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(
            EntityManagerInterface::class,
            [
                'persist' => Expected::once(),
                'flush'   => Expected::once(),
            ]
        );
        /** @var UserRepository $repository */
        $repository = $this->makeEmpty(UserRepository::class);
        $service = new UserManager($em, $repository);

        $service->save(new User());
    }
}