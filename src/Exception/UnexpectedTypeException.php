<?php
declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;

/**
 * Class UnexpectedTypeException
 *
 * @package App\Exception
 */
class UnexpectedTypeException extends InvalidArgumentException implements AppExceptionInterface
{
    /**
     * UnexpectedTypeException constructor.
     *
     * @param mixed  $value
     * @param string $expectedType
     */
    public function __construct($value, string $expectedType)
    {
        parent::__construct(
            sprintf('Expected argument of type "%s", "%s" given', $expectedType, get_debug_type($value))
        );
    }
}