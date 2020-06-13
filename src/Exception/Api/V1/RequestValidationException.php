<?php
declare(strict_types=1);

namespace App\Exception\Api\V1;

use App\Exception\ValidationExceptionInterface;
use App\Exception\ValidationExceptionTrait;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class RequestValidationException
 *
 * @package App\Exception\Api\V1
 */
class RequestValidationException extends BadRequestHttpException implements ValidationExceptionInterface
{
    use ValidationExceptionTrait;

    /**
     * RequestValidationException constructor.
     *
     * @param ConstraintViolationListInterface $violations
     * @param string|null                      $message
     * @param \Throwable|null                  $previous
     * @param int                              $code
     * @param array                            $headers
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = null,
        \Throwable $previous = null,
        int $code = 0,
        array $headers = []
    )
    {
        $this->violations = $violations;
        parent::__construct($message, $previous, $code, $headers);
    }
}