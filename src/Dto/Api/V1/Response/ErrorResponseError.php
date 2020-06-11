<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ErrorResponseError
 *
 * @package App\Dto\Api\V1\Response
 */
class ErrorResponseError
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("field")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Request field", example="username")
     */
    protected string $field;

    /**
     * Error message
     *
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("message")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Error message", example="This field should not be blank.")
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