<?php
declare(strict_types=1);

namespace App\Service\Request;

use App\Exception\Api\V1\RequestValidationException;

/**
 * Interface RequestValidatorInterface
 *
 * @package App\Service\Request
 */
interface RequestValidatorInterface
{
    /**
     * @param object $object
     *
     * @throws RequestValidationException
     */
    public function validateRequest(object $object): void;
}