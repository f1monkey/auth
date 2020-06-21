<?php
declare(strict_types=1);

namespace App\Exception\Api\V1;

use F1Monkey\RequestHandleBundle\Exception\UserFriendlyExceptionInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class GenericForbiddenHttpException
 *
 * @package App\Exception\Api\V1
 */
class GenericForbiddenHttpException extends RuntimeException implements
    ApiV1ExceptionInterface,
    UserFriendlyExceptionInterface,
    HttpExceptionInterface
{
    /**
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return Response::HTTP_FORBIDDEN;
    }

    /**
     * @return array<string, string> Response headers
     */
    public function getHeaders()
    {
        return [];
    }
}