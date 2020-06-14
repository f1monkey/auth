<?php
declare(strict_types=1);

namespace App\Tests\unit\Service\Request;

use App\Exception\Api\V1\RequestValidationException;
use App\Service\Request\RequestValidator;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;
use stdClass;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestValidatorTest
 *
 * @package App\Tests\unit\Service\Request
 */
class RequestValidatorTest extends Unit
{
    /**
     * @throws RequestValidationException
     * @throws Exception
     */
    public function testCannotThrowExceptionOnValidRequest()
    {
        $violations = $this->makeEmpty(
            ConstraintViolationListInterface::class,
            [
                'count' => Expected::atLeastOnce(0),
            ]
        );
        /** @var ValidatorInterface $validator */
        $validator = $this->makeEmpty(
            ValidatorInterface::class,
            [
                'validate' => Expected::once($violations),
            ]
        );

        $service = new RequestValidator($validator);
        $service->validateRequest(new stdClass());
    }

    /**
     * @throws RequestValidationException
     * @throws Exception
     */
    public function testCanThrowExceptionOnInvalidRequest()
    {
        $violations = $this->makeEmpty(
            ConstraintViolationListInterface::class,
            [
                'count' => Expected::atLeastOnce(1),
            ]
        );
        /** @var ValidatorInterface $validator */
        $validator = $this->makeEmpty(
            ValidatorInterface::class,
            [
                'validate' => Expected::once($violations),
            ]
        );

        $service = new RequestValidator($validator);

        $this->expectException(RequestValidationException::class);
        $service->validateRequest(new stdClass());
    }
}