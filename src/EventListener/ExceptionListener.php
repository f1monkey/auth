<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Factory\ErrorResponseFactoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Class ExceptionListener
 *
 * @package App\EventListener
 */
class ExceptionListener implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const DEBUG_HEADER = 'X-Debug';

    /**
     * @var ErrorResponseFactoryInterface
     */
    protected ErrorResponseFactoryInterface $errorResponseFactory;

    /**
     * @var bool
     */
    protected bool $showExceptions;

    /**
     * ExceptionListener constructor.
     *
     * @param ErrorResponseFactoryInterface $errorResponseFactory
     * @param bool                          $showExceptions
     */
    public function __construct(
        ErrorResponseFactoryInterface $errorResponseFactory,
        bool $showExceptions
    )
    {
        $this->errorResponseFactory = $errorResponseFactory;
        $this->showExceptions = $showExceptions;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $this->logException($exception);

        if (!$this->needCreateErrorResponse($event->getRequest())) {
            return;
        }

        $response = $this->errorResponseFactory->createFromException($exception);

        $event->setResponse(
            new JsonResponse($response, $this->getHttpsStatus($exception))
        );
    }

    /**
     * @param Throwable $exception
     */
    protected function logException(Throwable $exception): void
    {
        // @todo
    }

    /**
     * @param Throwable $exception
     *
     * @return int
     */
    protected function getHttpsStatus(Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * @param Request   $request
     * @param Throwable $exception
     *
     * @return bool
     */
    protected function needCreateErrorResponse(Request $request): bool
    {
        if (!$this->showExceptions) {
            return true;
        }

        if ($request->headers->get(static::DEBUG_HEADER) === null) {
            return true;
        }

        return false;
    }
}