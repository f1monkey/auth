<?php
declare(strict_types=1);

namespace App\Tests\api\v1;

use ApiTester;
use App\DataFixtures\UserFixtures;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DocCest
 *
 * @package App\Tests\api\v1
 */
class DocCest
{
    /**
     * @param ApiTester $I
     */
    public function canGetApiDocs(ApiTester $I)
    {
        $I->sendGET('/v1/doc',);
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }
}