<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Factory\Api\V1\ErrorResponseFactoryInterface;
use JMS\Serializer\ArrayTransformerInterface;
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
     * @var ArrayTransformerInterface
     */
    protected ArrayTransformerInterface $arrayTransformer;

    /**
     * @var bool
     */
    protected bool $showExceptions;

    /**
     * ExceptionListener constructor.
     *
     * @param ErrorResponseFactoryInterface $errorResponseFactory
     * @param ArrayTransformerInterface     $arrayTransformer
     * @param bool                          $showExceptions
     */
    public function __construct(
        ErrorResponseFactoryInterface $errorResponseFactory,
        ArrayTransformerInterface $arrayTransformer,
        bool $showExceptions
    )
    {
        $this->errorResponseFactory = $errorResponseFactory;
        $this->arrayTransformer     = $arrayTransformer;
        $this->showExceptions       = $showExceptions;
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
            new JsonResponse($this->arrayTransformer->toArray($response), $this->getHttpsStatus($exception))
        );
    }

    /**
     * @param Throwable $exception
     */
    protected function logException(Throwable $exception): void
    {
        if ($exception instanceof HttpExceptionInterface) {
            $this->logger->info($exception);
        } else {
            $this->logger->critical($exception);
        }
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
     * @param Request $request
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