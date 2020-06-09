<?php
declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Trait ValidationExceptionTrait
 *
 * @package App\Exception
 */
trait ValidationExceptionTrait
{
    /**
     * @var ConstraintViolationListInterface
     */
    protected ConstraintViolationListInterface $violations;

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}