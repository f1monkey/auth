<?php
declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Interface ValidationExceptionInterface
 *
 * @package App\Exception
 */
interface ValidationExceptionInterface extends AppExceptionInterface
{
    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface;
}