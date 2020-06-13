<?php
declare(strict_types=1);

namespace App\Exception\Api\V1;

use App\Exception\Api\BadRequestExceptionInterface;
use App\Exception\UserFriendlyExceptionInterface;
use App\Exception\ValidationExceptionInterface;
use App\Exception\ValidationExceptionTrait;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Class RequestValidationException
 *
 * @package App\Exception\Api\V1
 */
class RequestValidationException extends RuntimeException implements
    ApiV1ExceptionInterface,
    HttpExceptionInterface,
    ValidationExceptionInterface,
    UserFriendlyExceptionInterface
{
    use ValidationExceptionTrait;

    /**
     * RequestValidationException constructor.
     *
     * @param ConstraintViolationListInterface $violations
     * @param string|null                      $message
     * @param Throwable|null                   $previous
     * @param int                              $code
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = null,
        Throwable $previous = null,
        int $code = 0
    )
    {
        $this->violations = $violations;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return Response::HTTP_BAD_REQUEST;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return [];
    }
}