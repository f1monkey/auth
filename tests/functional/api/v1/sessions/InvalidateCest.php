<?php
declare(strict_types=1);

namespace App\Tests\functional\api\v1\sessions;

use FunctionalTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvalidateCest
 *
 * @package App\Tests\functional\api\v1\sessions
 */
class InvalidateCest
{
    /**
     * @param FunctionalTester $I
     */
    public function canInvalidateUserSession(FunctionalTester $I)
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
     * @param FunctionalTester $I
     */
    public function cannotInvalidateAnotherUserSession(FunctionalTester $I)
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