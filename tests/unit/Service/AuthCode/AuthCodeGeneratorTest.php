<?php
declare(strict_types=1);

namespace App\Tests\unit\Service\AuthCode;

use App\Service\AuthCode\AuthCodeGenerator;
use Codeception\Test\Unit;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class AuthCodeGeneratorTest
 *
 * @package App\Tests\unit\Service\Security
 */
class AuthCodeGeneratorTest extends Unit
{
    /**
     * @dataProvider authCodeParametersProvider
     *
     * @param int $length
     *
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testCanGenerateValidAuthCode(int $length)
    {
        $service = new AuthCodeGenerator();
        $result  = $service->generate($length);

        static::assertSame($length, strlen($result));
    }

    /**
     * @return array
     */
    public function authCodeParametersProvider(): array
    {
        return [
            [5],
            [10],
            [128],
        ];
    }
}