<?php
declare(strict_types=1);

namespace App\Tests\integration\EventListener;

use App\DataFixtures\UserFixtures;
use App\Tests\integration\AbstractIntegrationTestCase;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Class SetUserInfoOnCreateListenerTest
 *
 * @package App\Tests\integration\EventListener
 */
class SetUserInfoOnCreateListenerTest extends AbstractIntegrationTestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testCanSetUserAgentAndUserIpOnRefreshTokenCreate()
    {
        $this->tester->loadFixtures(UserFixtures::class);

        /** @var UserInterface $user */
        $user  = $this->makeEmpty(UserInterface::class, [
            'getUsername' => UserFixtures::USER_1_USERNAME
        ]);
        /** @var TokenInterface $token */
        $token = $this->makeEmpty(
            TokenInterface::class,
            [
                'getUser' => $user,
            ]
        );

        $expectedUserIp = '123.123.123.123';
        $expectedUserAgent = 'user-agent';
        $server = [
            'REMOTE_ADDR' => $expectedUserIp,
            'HTTP_USER_AGENT' => $expectedUserAgent
        ];
        $request = Request::create('/', 'GET', [], [], [], $server);
        /** @var RequestStack $requestStack */
        $requestStack = $this->tester->grabService('request_stack');
        $requestStack->push($request);

        /** @var AuthenticationSuccessHandlerInterface $jwtService */
        $jwtService = $this->tester->grabService('lexik_jwt_authentication.handler.authentication_success');
        $jwtService->onAuthenticationSuccess($request, $token);

        $this->tester->seeInDatabase('refresh_token', ['username' => UserFixtures::USER_1_USERNAME]);
        $userIp = $this->tester->grabFromDatabase('refresh_token', 'ip', ['username' => UserFixtures::USER_1_USERNAME]);
        $userAgent = $this->tester->grabFromDatabase('refresh_token', 'user_agent', ['username' => UserFixtures::USER_1_USERNAME]);

        static::assertSame($expectedUserIp, $userIp);
        static::assertSame($expectedUserAgent, $userAgent);
    }
}