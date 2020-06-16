<?php
declare(strict_types=1);

namespace App\Tests\integration\Service\AuthCode;

use App\Exception\AuthCode\TooManyAuthCodesException;
use App\Service\AuthCode\AuthCodeManagerInterface;
use App\Tests\integration\AbstractIntegrationTestCase;
use DateTimeImmutable;

/**
 * Class AuthCodeManagerTest
 *
 * @package App\Tests\integration\Service\AuthCode
 */
class AuthCodeManagerTest extends AbstractIntegrationTestCase
{
    /**
     * @throws TooManyAuthCodesException
     */
    public function testCanCreateUserAuthCodes()
    {
        $maxCount = $this->tester->grabParameter('app.auth_code.max_per_user');
        /** @var AuthCodeManagerInterface $service */
        $service = $this->tester->grabService('test.app.auth_code_manager');

        $user = $this->tester->createUser('user');
        $this->tester->haveInRepository($user);

        for ($i = 0; $i < $maxCount; $i++) {
            $authCode = $service->createForUser($user);
            $this->tester->canSeeInDatabase(
                'auth_code',
                ['code' => $authCode->getCode(), 'parent_user_id' => $user->getId()]
            );
        }
    }

    /**
     * @throws TooManyAuthCodesException
     */
    public function testCannotCreateMoreThanMaximumCountUserAuthCodes()
    {
        $maxCount = $this->tester->grabParameter('app.auth_code.max_per_user');
        /** @var AuthCodeManagerInterface $service */
        $service = $this->tester->grabService('test.app.auth_code_manager');

        $user = $this->tester->createUser('user');
        $this->tester->haveInRepository($user);

        $this->expectException(TooManyAuthCodesException::class);
        for ($i = 0; $i < $maxCount + 1; $i++) {
            $service->createForUser($user);
        }
    }

    /**
     * @throws TooManyAuthCodesException
     */
    public function testDoesNotCountInactiveUserAuthCodes()
    {
        $maxCount = $this->tester->grabParameter('app.auth_code.max_per_user');
        $lifetime = $this->tester->grabParameter('app.auth_code.lifetime');
        /** @var AuthCodeManagerInterface $service */
        $service = $this->tester->grabService('test.app.auth_code_manager');

        $user = $this->tester->createUser('user');
        $this->tester->haveInRepository($user);

        $outdatedAuthCode = $service->createForUser($user);
        $outdatedAuthCode->setInvalidateAt(new DateTimeImmutable(sprintf('-%s seconds', $lifetime)));
        $this->tester->haveInRepository($outdatedAuthCode);

        for ($i = 0; $i < $maxCount; $i++) {
            $authCode = $service->createForUser($user);
            $this->tester->canSeeInDatabase(
                'auth_code',
                ['code' => $authCode->getCode(), 'parent_user_id' => $user->getId()]
            );
        }
    }
}