<?php
declare(strict_types=1);

namespace App\Tests\unit\Service\User;

use App\Entity\RefreshToken;
use App\Entity\User;
use App\Exception\Entity\EntityNotFoundException;
use App\Repository\RefreshTokenRepository;
use App\Service\User\UserSessionManager;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class UserSessionManagerTest
 *
 * @package App\Tests\unit\Service\User
 */
class UserSessionManagerTest extends Unit
{
    /**
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testCanGetAllTokensByUser()
    {
        $expected = new RefreshToken();
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var RefreshTokenRepository $repository */
        $repository = $this->makeEmpty(
            RefreshTokenRepository::class,
            [
                'findByUsername' => [$expected],
            ]
        );
        $service    = new UserSessionManager($repository, $em);
        /** @var User $user */
        $user = $this->makeEmpty(
            User::class,
            [
                'getUsername' => 'user',
            ]
        );

        $result = $service->getByUser($user);
        static::assertSame($result->first(), $expected);
    }

    /**
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function testCanGetExistingTokenByIdAndUser()
    {
        $expected = new RefreshToken();
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var RefreshTokenRepository $repository */
        $repository = $this->makeEmpty(
            RefreshTokenRepository::class,
            [
                'findByIdAndUsername' => Expected::once($expected),
            ]
        );
        $service    = new UserSessionManager($repository, $em);
        /** @var User $user */
        $user = $this->makeEmpty(
            User::class,
            [
                'getUsername' => 'user',
            ]
        );

        $result = $service->getById($user, 'id');
        static::assertSame($result, $expected);
    }

    /**
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function testCannotGetNotExistingTokenByIdAndUser()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var RefreshTokenRepository $repository */
        $repository = $this->makeEmpty(
            RefreshTokenRepository::class,
            [
                'findByIdAndUsername' => Expected::once(null),
            ]
        );
        $service    = new UserSessionManager($repository, $em);
        /** @var User $user */
        $user = $this->makeEmpty(
            User::class,
            [
                'getUsername' => 'user',
            ]
        );

        $this->expectException(EntityNotFoundException::class);
        $service->getById($user, 'id');
    }

    /**
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function testCanGetExistingTokenByItsValue()
    {
        $expected = new RefreshToken();
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var RefreshTokenRepository $repository */
        $repository = $this->makeEmpty(
            RefreshTokenRepository::class,
            [
                'findByTokenValue' => Expected::once($expected),
            ]
        );
        $service    = new UserSessionManager($repository, $em);

        $result = $service->getByTokenValue('token');
        static::assertSame($result, $expected);
    }

    /**
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function testCannotGetNotExistingTokenByItsValue()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var RefreshTokenRepository $repository */
        $repository = $this->makeEmpty(
            RefreshTokenRepository::class,
            [
                'findByTokenValue' => Expected::once(null),
            ]
        );
        $service    = new UserSessionManager($repository, $em);

        $this->expectException(EntityNotFoundException::class);
        $service->getByTokenValue('token');
    }

    /**
     * @throws Exception
     */
    public function testCanSaveToken()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(
            EntityManagerInterface::class,
            [
                'persist' => Expected::once(),
                'flush'   => Expected::once(),
            ]
        );
        /** @var RefreshTokenRepository $repository */
        $repository = $this->makeEmpty(RefreshTokenRepository::class,);
        $service    = new UserSessionManager($repository, $em);

        $service->save(new RefreshToken());
    }

    /**
     * @throws Exception
     */
    public function testCanDeleteToken()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(
            EntityManagerInterface::class,
            [
                'remove' => Expected::once(),
                'flush'  => Expected::once(),
            ]
        );
        /** @var RefreshTokenRepository $repository */
        $repository = $this->makeEmpty(RefreshTokenRepository::class,);
        $service    = new UserSessionManager($repository, $em);

        $service->delete(new RefreshToken());
    }
}