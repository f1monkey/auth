<?php
declare(strict_types=1);

namespace App\Tests\unit\Service\AuthCode;

use App\Entity\AuthCode;
use App\Entity\User;
use App\Exception\AuthCode\TooManyAuthCodesException;
use App\Exception\Entity\EntityNotFoundException;
use App\Factory\AuthCode\AuthCodeEntityFactoryInterface;
use App\Repository\AuthCodeRepository;
use App\Service\AuthCode\AuthCodeManager;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class AuthCodeManagerTest
 *
 * @package App\Tests\unit\Service\User
 */
class AuthCodeManagerTest extends Unit
{
    /**
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testCanGetAuthCodeByUser()
    {
        $expected = $this->makeEmpty(AuthCode::class);
        /** @var AuthCodeEntityFactoryInterface $factory */
        $factory = $this->makeEmpty(AuthCodeEntityFactoryInterface::class);
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var AuthCodeRepository $repo */
        $repo = $this->makeEmpty(
            AuthCodeRepository::class,
            [
                'findOneActiveByUserAndCode' => Expected::once($expected),
            ]
        );
        /** @var User $user */
        $user = $this->makeEmpty(User::class);

        $service = new AuthCodeManager($factory, $repo, $em);
        $result  = $service->get($user, 'qwerty');

        static::assertSame($expected, $result);
    }

    /**
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function testCannotGetNotExistingAuthCode()
    {
        /** @var AuthCodeEntityFactoryInterface $factory */
        $factory = $this->makeEmpty(AuthCodeEntityFactoryInterface::class);
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(EntityManagerInterface::class);
        /** @var AuthCodeRepository $repo */
        $repo = $this->makeEmpty(
            AuthCodeRepository::class,
            [
                'findOneActiveByUserAndCode' => Expected::once(null),
            ]
        );
        /** @var User $user */
        $user = $this->makeEmpty(User::class);

        $service = new AuthCodeManager($factory, $repo, $em);

        $this->expectException(EntityNotFoundException::class);
        $service->get($user, 'qwerty');
    }

    /**
     * @throws ExpectationFailedException
     * @throws TooManyAuthCodesException
     * @throws Exception
     */
    public function testCanCreateAuthCodeForUser()
    {
        $max      = 5;
        $expected = $this->makeEmpty(AuthCode::class);
        /** @var AuthCodeEntityFactoryInterface $factory */
        $factory = $this->makeEmpty(
            AuthCodeEntityFactoryInterface::class,
            [
                'createForUser' => Expected::once($expected),
            ]
        );
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(
            EntityManagerInterface::class,
            [
                'persist' => Expected::once(),
                'flush'   => Expected::once(),
            ]
        );
        /** @var AuthCodeRepository $repo */
        $repo = $this->makeEmpty(
            AuthCodeRepository::class,
            [
                'countActiveByUser' => Expected::once($max - 1),
            ]
        );
        /** @var User $user */
        $user    = $this->makeEmpty(User::class);
        $service = new AuthCodeManager($factory, $repo, $em, $max);

        $result = $service->createForUser($user);

        static::assertSame($expected, $result);
    }

    /**
     * @throws TooManyAuthCodesException
     * @throws Exception
     */
    public function testCannotCreateMoreThanMaxAuthCodesForUser()
    {
        $max = 5;
        /** @var AuthCodeEntityFactoryInterface $factory */
        $factory = $this->makeEmpty(AuthCodeEntityFactoryInterface::class);
        /** @var EntityManagerInterface $em */
        $em = $this->makeEmpty(
            EntityManagerInterface::class,
            [
                'persist' => Expected::never(),
                'flush'   => Expected::never(),
            ]
        );
        /** @var AuthCodeRepository $repo */
        $repo = $this->makeEmpty(
            AuthCodeRepository::class,
            [
                'countActiveByUser' => Expected::once($max),
            ]
        );
        /** @var User $user */
        $user    = $this->makeEmpty(User::class);
        $service = new AuthCodeManager($factory, $repo, $em, $max);

        $this->expectException(TooManyAuthCodesException::class);
        $service->createForUser($user);
    }
}