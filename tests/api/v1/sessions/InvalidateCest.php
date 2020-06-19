<?php
declare(strict_types=1);

namespace App\Tests\api\v1\sessions;

use ApiTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvalidateCest
 *
 * @package App\Tests\api\v1\sessions
 */
class InvalidateCest
{
    /**
     * @param ApiTester $I
     */
    public function canInvalidateUserSession(ApiTester $I)
    {
        $user = $I->createUser();
        $I->haveInRepository($user);
        $token = $I->createRefreshToken($user->getUsernameCanonical());
        $I->haveInRepository($token);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amJwtAuthorizedAs($user);
        $I->sendDELETE(sprintf('/v1/sessions/%s', $token->getId()));

        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseMatchesJsonType(
            [
                'id'        => 'string',
                'createdAt' => 'string',
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function cannotInvalidateAnotherUserSession(ApiTester $I)
    {
        $user = $I->createUser('user');
        $I->haveInRepository($user);
        $token = $I->createRefreshToken('another-user');
        $I->haveInRepository($token);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amJwtAuthorizedAs($user);
        $I->sendDELETE(sprintf('/v1/sessions/%s', $token->getId()));

        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }
}