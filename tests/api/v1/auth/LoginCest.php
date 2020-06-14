<?php
declare(strict_types=1);

namespace App\Tests\api\v1\auth;

use ApiTester;
use App\DataFixtures\UserFixtures;
use Codeception\Example;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginCest
 *
 * @package App\Tests\api\v1\auth
 */
class LoginCest
{
    /**
     * @dataProvider validCredentialsProvider
     *
     * @param ApiTester $I
     * @param Example   $example
     */
    public function canLoginWithValidCredentials(ApiTester $I, Example $example)
    {
        $I->loadFixtures(UserFixtures::class);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/login',
            [
                'username' => $example['username'],
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

    /**
     *
     */
    protected function validCredentialsProvider(): array
    {
        return [
            ['username' => UserFixtures::USER_1_USERNAME],
            ['username' => UserFixtures::USER_1_EMAIL],
        ];
    }
}