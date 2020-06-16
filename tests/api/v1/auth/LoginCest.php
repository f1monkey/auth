<?php
declare(strict_types=1);

namespace App\Tests\api\v1\auth;

use ApiTester;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginCest
 *
 * @package App\Tests\api\v1\auth
 */
class LoginCest
{
    /**
     * @param ApiTester $I
     */
    public function canGetAuthCodeByUsername(ApiTester $I)
    {
        $user = $this->createUser($I);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/login',
            [
                'username' => $user->getUsername(),
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseMatchesJsonType(
            [
                'username' => 'string',
                'email'    => 'string',
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function canGetAuthCodeByEmail(ApiTester $I)
    {
        $user = $this->createUser($I);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/login',
            [
                'username' => $user->getEmail(),
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseMatchesJsonType(
            [
                'username' => 'string',
                'email'    => 'string',
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function cannotGetAuthCodeWithInvalidUsername(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/login',
            [
                'username' => 'invalid-email@example.com',
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
}