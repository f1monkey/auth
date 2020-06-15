<?php
declare(strict_types=1);

namespace App\Exception\AuthCode;

use RuntimeException;

/**
 * Class UnableToCreateAuthCodeException
 *
 * @package App\Exception\AuthCode
 */
class TooManyAuthCodesException extends RuntimeException implements AuthCodeExceptionInterface
{

}