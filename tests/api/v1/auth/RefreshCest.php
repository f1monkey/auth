<?php
declare(strict_types=1);

namespace App\Tests\api\v1\auth;

use ApiTester;
use App\DataFixtures\UserFixtures;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RefreshCest
 *
 * @package App\Tests\api\v1\auth
 */
class RefreshCest
{
    /**
     * @param ApiTester $I
     */
    public function canRefreshAuthTokenWithValidRefreshToken(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);
        $token = $I->createRefreshToken(UserFixtures::USER_1_USERNAME);
        $I->haveInRepository($token);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/refresh',
            [
                'refreshToken' => $token->getRefreshToken(),
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
    public function cannotRefreshAuthTokenWithInvalidRefreshToken(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);
        $token = $I->createRefreshToken(UserFixtures::USER_1_USERNAME);
        $I->haveInRepository($token);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/refresh',
            [
                'refreshToken' => 'invalid-token',
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }
}