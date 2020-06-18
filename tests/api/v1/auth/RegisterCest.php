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
                'username' => $example['username'],
                'email'    => $example['email'],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson(
            [
                'username' => $example['username'],
                'email'    => $example['maskedEmail'],
            ]
        );
    }

    /**
     * @dataprovider validDataProvider
     *
     * @param ApiTester $I
     * @param Example   $example
     */
    public function cannotRegisterWithTheSameUsernameOrEmailTwice(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/register',
            [
                'username' => $example['username'],
                'email'    => $example['email'],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->sendPOST(
            '/v1/auth/register',
            [
                'username' => $example['username'],
                'email'    => $example['email'],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataprovider invalidDataProvider
     *
     * @param ApiTester $I
     * @param Example   $example
     */
    public function cannotRegisterWithInvalidData(ApiTester $I, Example $example)
    {
        $I->loadFixtures(UserFixtures::class);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/register',
            [
                'username' => $example['username'],
                'email'    => $example['email'],
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
                'username' => 'user',
                'email'    => 'user@example.com',
                'maskedEmail' => 'u**********@example.com'
            ],
            [
                'username' => 'qwerty',
                'email'    => 'zxcvbn@example.com',
                'maskedEmail' => 'z**********@example.com'
            ],
        ];
    }

    /**
     * @return array|string[]
     */
    protected function invalidDataProvider(): array
    {
        return [
            // username is blank
            [
                'username' => '',
                'email'    => 'user@example.com',
            ],
            // email is blank
            [
                'username' => 'user',
                'email'    => '',
            ],
            // email is not a valid email address
            [
                'username' => 'user',
                'email'    => 'invalid-email',
            ],
        ];
    }
}