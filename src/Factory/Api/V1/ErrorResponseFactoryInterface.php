<?php
declare(strict_types=1);

namespace App\Factory\Api\V1;

use App\Dto\Api\V1\Response\ErrorResponse;
use Throwable;

/**
 * Interface ErrorResponseFactoryInterface
 *
 * @package App\Factory\Api\V1
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