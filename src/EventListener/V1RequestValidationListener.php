<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Dto\Api\V1\Request\V1RequestInterface;
use App\Exception\Api\V1\RequestValidationException;
use App\Service\Request\RequestValidatorInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class V1RequestValidationListener
 *
 * @package App\EventListener
 */
class V1RequestValidationListener
{
    /**
     * @var RequestValidatorInterface
     */
    protected RequestValidatorInterface $requestValidator;

    /**
     * V1RequestValidationListener constructor.
     *
     * @param RequestValidatorInterface $validator
     */
    public function __construct(RequestValidatorInterface $validator)
    {
        $this->requestValidator = $validator;
    }

    /**
     * @param ControllerArgumentsEvent $event
     *
     * @throws RequestValidationException
     */
    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        /** @var ConstraintViolationListInterface|null $violations */
        $violations = null;
        foreach ($event->getArguments() as $argument) {
            if (!$argument instanceof V1RequestInterface) {
                continue;
            }

            $this->requestValidator->validateRequest($argument);
        }
    }
}