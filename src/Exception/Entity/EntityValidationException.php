<?php
declare(strict_types=1);

namespace App\Exception\Entity;

use F1Monkey\RequestHandleBundle\Exception\Validation\HasViolationsTrait;
use F1Monkey\RequestHandleBundle\Exception\Validation\ValidationExceptionInterface;
use RuntimeException;
use Stringable;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Class EntityValidationException
 *
 * @package App\Exception\Doctrine
 */
class EntityValidationException extends RuntimeException implements EntityExceptionInterface, ValidationExceptionInterface
{
    use HasViolationsTrait;

    /**
     * EntityValidationException constructor.
     *
     * @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations
     * @param string                                                         $message
     * @param int                                                            $code
     * @param Throwable|null                                                 $previous
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        $message = '',
        $code = 0,
        Throwable $previous = null
    )
    {
        if ($message === '') {
            if ($violations instanceof Stringable) {
                $message = sprintf('Validation error: %s', (string)$violations);
            } else {
                $message = 'Validation error';
            }
        }

        parent::__construct($message, $code, $previous);
        $this->violations = $violations;
    }
}