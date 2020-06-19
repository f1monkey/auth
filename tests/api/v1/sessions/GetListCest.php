<?php
declare(strict_types=1);

namespace App\Tests\api\v1\sessions;

use ApiTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GetListCest
 *
 * @package App\Tests\api\v1\sessions
 */
class GetListCest
{
    /**
     * @param ApiTester $I
     */
    public function canGetUserSessionList(ApiTester $I)
    {
        $user = $I->createUser();
        $I->haveInRepository($user);
        $token = $I->createRefreshToken($user->getUsernameCanonical());
        $I->haveInRepository($token);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amJwtAuthorizedAs($user);
        $I->sendGET('/v1/sessions');

        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseMatchesJsonType(
            [
                'items' => [
                    [
                        'id'        => 'string',
                        'createdAt' => 'string',
                        'userData' => [
                            'browser' => 'string|null',
                            'platform' => 'string|null',
                            'ip' => 'string|null'
                        ]
                    ],
                ],
            ]
        );
    }
}