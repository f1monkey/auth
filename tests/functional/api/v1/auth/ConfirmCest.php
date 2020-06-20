<?php
declare(strict_types=1);

namespace App\Tests\functional\api\v1\auth;

use FunctionalTester;
use App\Entity\AuthCode;
use App\Entity\User;
use Codeception\Example;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginCest
 *
 * @package App\Tests\functional\api\v1\auth
 */
class ConfirmCest
{
    /**
     * @dataProvider authCodeProvider
     *
     * @param FunctionalTester $I
     * @param Example          $example
     */
    public function canLoginWithValidAuthCodeByUsername(FunctionalTester $I, Example $example)
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
     * @param FunctionalTester $I
     */
    public function canLoginWithValidAuthCodeByEmail(FunctionalTester $I)
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
     * @param FunctionalTester $I
     */
    public function cannotLoginWithInvalidUsername(FunctionalTester $I)
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
     * @param FunctionalTester $I
     */
    public function cannotLoginWithInvalidAuthCode(FunctionalTester $I)
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
     * @param FunctionalTester $I
     */
    public function cannotLoginWithoutAuthCode(FunctionalTester $I)
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
     * @param FunctionalTester $I
     */
    public function cannotLoginWithTheSameAuthCodeTwice(FunctionalTester $I)
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
     * @param FunctionalTester $I
     *
     * @return User
     */
    protected function createUser(FunctionalTester $I): User
    {
        $username = 'user';
        $email    = 'user@domain.local';
        $user     = $I->createUser($username, $email);
        $I->haveInRepository($user);

        return $user;
    }

    /**
     * @param FunctionalTester $I
     * @param User             $user
     * @param string           $authCode
     *
     * @return AuthCode
     */
    protected function createAuthCode(FunctionalTester $I, User $user, string $authCode = 'qwerty'): AuthCode
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