<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Dto\Api\V1\Request\V1RequestInterface;
use App\Exception\Api\V1\RequestValidationException;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class V1RequestValidationListener
 *
 * @package App\EventListener
 */
class V1RequestValidationListener
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * V1RequestValidationListener constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
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

            $violations = $violations === null
                ? $this->validator->validate($argument)
                : $violations->addAll($this->validator->validate($argument));
        }

        if ($violations && $violations->count()) {
            throw new RequestValidationException($violations, 'Validation error');
        }
    }
}