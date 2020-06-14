<?php
declare(strict_types=1);

namespace App\Exception\Api\V1;

use App\Exception\UserFriendlyExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class UnauthorizedHttpException
 *
 * @package App\Exception\Api\V1
 */
class UnauthorizedHttpException extends \RuntimeException implements ApiV1ExceptionInterface, UserFriendlyExceptionInterface, HttpExceptionInterface
{
    /**
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    /**
     * @return array Response headers
     */
    public function getHeaders()
    {
        return [];
    }
}