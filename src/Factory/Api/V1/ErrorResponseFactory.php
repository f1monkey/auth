<?php
declare(strict_types=1);

namespace App\Factory\Api\V1;

use App\Dto\Api\V1\Response\ErrorResponse;
use App\Dto\Api\V1\Response\ErrorResponseError;
use App\Exception\UserFriendlyExceptionInterface;
use App\Exception\ValidationExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

/**
 * Class ErrorResponseFactory
 *
 * @package App\Factory\Api\V1
 */
class ErrorResponseFactory implements ErrorResponseFactoryInterface
{
    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * ErrorResponseFactory constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Throwable $exception
     *
     * @return ErrorResponse
     */
    public function createFromException(Throwable $exception): ErrorResponse
    {
        return new ErrorResponse($this->getErrorMessage($exception), $this->getRequestErrors($exception));
    }

    /**
     * @param Throwable $exception
     *
     * @return string
     */
    protected function getErrorMessage(Throwable $exception): string
    {
        if ($exception instanceof UserFriendlyExceptionInterface) {
            return $this->translator->trans($exception->getMessage());
        }

        if ($exception instanceof HttpExceptionInterface) {
            return Response::$statusTexts[$exception->getStatusCode()];
        }

        return Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR];
    }

    /**
     * @param Throwable $exception
     *
     * @return Collection|ErrorResponseError[]|null
     */
    protected function getRequestErrors(Throwable $exception): ?Collection
    {
        if (!$exception instanceof ValidationExceptionInterface) {
            return null;
        }

        if (!$exception instanceof UserFriendlyExceptionInterface) {
            return null;
        }

        $messages = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($exception->getViolations() as $violation) {
            $messages[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        $result = new ArrayCollection();
        foreach ($messages as $path => $messageGroup) {
            $result->add(
                new ErrorResponseError($path, implode(' ', $messageGroup))
            );
        }

        return $result;
    }
}