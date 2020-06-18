<?php
declare(strict_types=1);

namespace App\Tests\api\v1\auth;

use ApiTester;
use App\Entity\AuthCode;
use App\Entity\User;
use Codeception\Example;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginCest
 *
 * @package App\Tests\api\v1\auth
 */
class ConfirmCest
{
    /**
     * @dataProvider authCodeProvider
     *
     * @param ApiTester $I
     * @param Example   $example
     */
    public function canLoginWithValidAuthCodeByUsername(ApiTester $I, Example $example)
    {
        $user     = $this->createUser($I);
        $authCode = $this->createAuthCode($I, $user, $example['create']);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/confirm',
            [
                'username' => $user->getUsername(),
                'authCode' => $example['verify'],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseMatchesJsonType(
            [
                'token'        => 'string',
                'refreshToken' => 'string',
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function canLoginWithValidAuthCodeByEmail(ApiTester $I)
    {
        $user     = $this->createUser($I);
        $authCode = $this->createAuthCode($I, $user);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/confirm',
            [
                'username' => $user->getEmail(),
                'authCode' => $authCode->getCode(),
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseMatchesJsonType(
            [
                'token'        => 'string',
                'refreshToken' => 'string',
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function cannotLoginWithInvalidUsername(ApiTester $I)
    {
        $user     = $this->createUser($I);
        $authCode = $this->createAuthCode($I, $user);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/confirm',
            [
                'username' => 'invalid-username',
                'authCode' => $authCode->getCode(),
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param ApiTester $I
     */
    public function cannotLoginWithInvalidAuthCode(ApiTester $I)
    {
        $user = $this->createUser($I);
        $this->createAuthCode($I, $user);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/confirm',
            [
                'username' => $user->getUsername(),
                'authCode' => 'invalid-code',
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param ApiTester $I
     */
    public function cannotLoginWithoutAuthCode(ApiTester $I)
    {
        $user = $this->createUser($I);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/confirm',
            [
                'username' => $user->getUsername(),
                'authCode' => 'invalid-code',
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param ApiTester $I
     */
    public function cannotLoginWithTheSameAuthCodeTwice(ApiTester $I)
    {
        $user     = $this->createUser($I);
        $authCode = $this->createAuthCode($I, $user);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/confirm',
            [
                'username' => $user->getUsername(),
                'authCode' => $authCode->getCode(),
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->sendPOST(
            '/v1/auth/confirm',
            [
                'username' => $user->getUsername(),
                'authCode' => $authCode->getCode(),
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param ApiTester $I
     *
     * @return User
     */
    protected function createUser(ApiTester $I): User
    {
        $username = 'user';
        $email    = 'user@domain.local';
        $user     = $I->createUser($username, $email);
        $I->haveInRepository($user);

        return $user;
    }

    /**
     * @param ApiTester $I
     * @param User      $user
     * @param string    $authCode
     *
     * @return AuthCode
     */
    protected function createAuthCode(ApiTester $I, User $user, string $authCode = 'qwerty'): AuthCode
    {
        $authCode = $I->createAuthCode($user, $authCode);
        $I->haveInRepository($authCode);

        return $authCode;
    }

    /**
     * @return \string[][]
     */
    protected function authCodeProvider()
    {
        return [
            ['create' => 'qwerty', 'verify' => 'QWERTY'],
            ['create' => 'QWERTY', 'verify' => 'qwerty'],
            ['create' => 'qwerty', 'verify' => 'qwerty'],
        ];
    }
}