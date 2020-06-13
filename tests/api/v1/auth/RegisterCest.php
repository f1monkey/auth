<?php
declare(strict_types=1);

namespace App\Tests\api\v1\auth;

use ApiTester;
use App\DataFixtures\UserFixtures;
use Codeception\Example;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegisterCest
 *
 * @package App\Tests\api\v1\auth
 */
class RegisterCest
{
    /**
     * @dataprovider validDataProvider
     *
     * @param ApiTester $I
     * @param Example   $example
     */
    public function canRegisterWithValidData(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/register',
            [
                'username'        => $example['username'],
                'password'        => $example['password'],
                'passwordConfirm' => $example['passwordConfirm'],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson(
            [
                'username' => $example['username'],
            ]
        );
    }

    /**
     * @dataprovider validDataProvider
     *
     * @param ApiTester $I
     * @param Example   $example
     */
    public function cannotRegisterWithTheSameUsernameTwice(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/register',
            [
                'username'        => $example['username'],
                'password'        => $example['password'],
                'passwordConfirm' => $example['passwordConfirm'],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->sendPOST(
            '/v1/auth/register',
            [
                'username'        => $example['username'],
                'password'        => $example['password'],
                'passwordConfirm' => $example['passwordConfirm'],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param ApiTester $I
     * @param Example   $example
     */
    public function cannotRegisterWithInvalidData(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/register',
            [
                'username'        => UserFixtures::USER_1_USERNAME,
                'password'        => '12345678',
                'passwordConfirm' => '12345678',
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }


    /**
     * @return array|string[]
     */
    protected function validDataProvider(): array
    {
        return [
            [
                'username'        => 'user@example.com',
                'password'        => 'password',
                'passwordConfirm' => 'password',
            ],
            [
                'username'        => 'zxcvbn@example.com',
                'password'        => '12345678',
                'passwordConfirm' => '12345678',
            ],
        ];
    }

    /**
     * @return array|string[]
     */
    protected function invalidDataProvider(): array
    {
        return [
            // password is too short
            [
                'username'        => 'user@example.com',
                'password'        => '1234',
                'passwordConfirm' => '1234',
            ],
            // password does not match the confirmation
            [
                'username'        => 'user@example.com',
                'password'        => '12345678',
                'passwordConfirm' => '876543321',
            ],
            // username is not a email
            [
                'username'        => 'zxcvbn',
                'password'        => '12345678',
                'passwordConfirm' => '12345678',
            ],
        ];
    }
}