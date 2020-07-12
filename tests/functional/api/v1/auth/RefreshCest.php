<?php
declare(strict_types=1);

namespace App\Tests\functional\api\v1\auth;

use App\DataFixtures\UserFixtures;
use FunctionalTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RefreshCest
 *
 * @package App\Tests\functional\api\v1\auth
 */
class RefreshCest
{
    /**
     * @param FunctionalTester $I
     */
    public function canRefreshAuthTokenWithValidRefreshToken(FunctionalTester $I)
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
                'sessionId'    => 'string',
            ]
        );
    }

    /**
     * @param FunctionalTester $I
     */
    public function cannotRefreshAuthTokenWithInvalidRefreshToken(FunctionalTester $I)
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