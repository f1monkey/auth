<?php
declare(strict_types=1);

namespace App\Tests\api\v1\auth;

use ApiTester;
use App\DataFixtures\UserFixtures;
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
    public function canLoginWithValidCredentials(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/login',
            [
                'username' => UserFixtures::USER_1_USERNAME
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseMatchesJsonType(
            [
                'token' => 'string',
                'refreshToken' => 'string'
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function cannotLoginWithInvalidCredentials(ApiTester $I)
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
}