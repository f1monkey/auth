<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

use Doctrine\Common\Collections\Collection;

/**
 * Class ErrorResponse
 *
 * @package App\Dto\Api\V1\Response
 */
class ErrorResponse
{
    /**
     * @var string
     */
    protected string $message;

    /**
     * @var Collection|ErrorResponseError[]
     */
    protected Collection $errors;

    /**
     * ErrorResponse constructor.
     *
     * @param string                          $message
     * @param ErrorResponseError[]|Collection $errors
     */
    public function __construct(string $message, Collection $errors)
    {
        $this->message = $message;
        $this->errors = $errors;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return ErrorResponseError[]|Collection
     */
    public function getErrors(): Collection
    {
        return $this->errors;
    }
}