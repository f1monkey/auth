<?php
declare(strict_types=1);

namespace App\Tests\functional\api\v1\auth;

use FunctionalTester;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Codeception\Example;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginCest
 *
 * @package App\Tests\functional\api\v1\auth
 */
class LoginCest
{
    /**
     * @dataProvider usernameProvider
     *
     * @param FunctionalTester $I
     * @param Example          $example
     */
    public function canGetAuthCodeByUsername(FunctionalTester $I, Example $example)
    {
        $this->createUser($I, $example['create']);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            '/v1/auth/login',
            [
                'username' => $example['verify'],
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
     * @param FunctionalTester $I
     */
    public function canGetAuthCodeByEmail(FunctionalTester $I)
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
     * @param FunctionalTester $I
     */
    public function cannotGetAuthCodeWithInvalidUsername(FunctionalTester $I)
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
     * @param FunctionalTester $I
     * @param string           $username
     *
     * @return User
     */
    protected function createUser(FunctionalTester $I, string $username = 'user'): User
    {
        $email = 'user@domain.local';
        $user  = $I->createUser($username, $email);
        $I->haveInRepository($user);

        return $user;
    }

    /**
     * @return array|\string[][]
     */
    protected function usernameProvider(): array
    {
        return [
            [
                'create' => 'user',
                'verify' => 'user',
            ],
            [
                'create' => 'user',
                'verify' => 'USER',
            ],
            [
                'create' => 'USER',
                'verify' => 'user',
            ],
        ];
    }
}