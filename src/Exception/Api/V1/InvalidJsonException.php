<?php
declare(strict_types=1);

namespace App\Exception\Api\V1;

use App\Exception\UserFriendlyExceptionInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class InvalidJsonException
 *
 * @package App\Exception\Api\V1
 */
class InvalidJsonException extends RuntimeException implements
    ApiV1ExceptionInterface,
    HttpExceptionInterface,
    UserFriendlyExceptionInterface
{

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