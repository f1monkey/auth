<?php
declare(strict_types=1);

namespace App\Exception\Entity;

use App\Exception\ValidationExceptionInterface;
use App\Exception\ValidationExceptionTrait;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Class EntityValidationException
 *
 * @package App\Exception\Doctrine
 */
class EntityValidationException extends RuntimeException implements EntityExceptionInterface, ValidationExceptionInterface
{
    use ValidationExceptionTrait;

    /**
     * EntityValidationException constructor.
     *
     * @param ConstraintViolationListInterface $violations
     * @param string                           $message
     * @param int                              $code
     * @param Throwable|null                   $previous
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        $message = $message ?: sprintf('Validation error: %s', (string)$violations);
        parent::__construct($message, $code, $previous);
        $this->violations = $violations;
    }
}