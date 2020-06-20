<?php
declare(strict_types=1);

namespace App\Tests\functional\api\v1;

use FunctionalTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DocCest
 *
 * @package App\Tests\functional\api\v1
 */
class DocCest
{
    /**
     * @param FunctionalTester $I
     */
    public function canGetApiDocs(FunctionalTester $I)
    {
        $I->sendGET('/v1/doc',);
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }
}