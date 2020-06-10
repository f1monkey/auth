<?php
declare(strict_types=1);

namespace App\Factory;

use App\Dto\Api\V1\Response\ErrorResponse;
use Throwable;

/**
 * Class ErrorResponseFactory
 *
 * @package App\Factory
 */
interface ErrorResponseFactoryInterface
{
    /**
     * @param Throwable $exception
     *
     * @return ErrorResponse
     */
    public function createFromException(Throwable $exception): ErrorResponse;
}