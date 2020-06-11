<?php
declare(strict_types=1);

namespace App\Factory\Api\V1;

use App\Dto\Api\V1\Response\ErrorResponse;
use App\Exception\UserFriendlyExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Class ErrorResponseFactory
 *
 * @package App\Factory\Api\V1
 */
class ErrorResponseFactory implements ErrorResponseFactoryInterface
{
    /**
     * @param Throwable $exception
     *
     * @return ErrorResponse
     */
    public function createFromException(Throwable $exception): ErrorResponse
    {
        // @todo process validation errors
        return new ErrorResponse($this->getErrorMessage($exception), new ArrayCollection());
    }

    /**
     * @param Throwable $exception
     *
     * @return string
     */
    protected function getErrorMessage(Throwable $exception): string
    {
        if ($exception instanceof UserFriendlyExceptionInterface) {
            return $exception->getMessage();
        }

        if ($exception instanceof HttpExceptionInterface) {
            return Response::$statusTexts[$exception->getStatusCode()];
        }

        return Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR];
    }
}