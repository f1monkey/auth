<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

/**
 * Class ErrorResponseError
 *
 * @package App\Dto\Api\V1\Response
 */
class ErrorResponseError
{
    /**
     * @var string
     */
    protected string $field;

    /**
     * @var string
     */
    protected string $message;

    /**
     * ErrorResponseError constructor.
     *
     * @param string $field
     * @param string $message
     */
    public function __construct(string $field, string $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}