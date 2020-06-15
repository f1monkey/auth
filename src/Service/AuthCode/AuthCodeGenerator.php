<?php
declare(strict_types=1);

namespace App\Service\AuthCode;

use Exception;

/**
 * Class AuthCodeGenerator
 *
 * @package App\Service\AuthCode
 */
class AuthCodeGenerator implements AuthCodeGeneratorInterface
{
    /**
     * @param int $length
     *
     * @return string
     * @throws Exception
     */
    public function generate(int $length = 6): string
    {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    }
}