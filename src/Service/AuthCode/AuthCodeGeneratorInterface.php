<?php
declare(strict_types=1);

namespace App\Service\AuthCode;

/**
 * Interface AuthCodeGeneratorInterface
 *
 * @package App\Service\AuthCode
 */
interface AuthCodeGeneratorInterface
{
    /**
     * @param int $length
     *
     * @return string
     */
    public function generate(int $length = 6): string;
}