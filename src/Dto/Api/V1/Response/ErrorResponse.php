<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ErrorResponse
 *
 * @package App\Dto\Api\V1\Response
 */
class ErrorResponse
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("message")
     * @Serializer\Type("string")
     *
     * @SWG\Property(description="Main error message", example="Validation error")
     */
    protected string $message;

    /**
     * Error collection (i.e. validation errors)
     *
     * @var Collection|ErrorResponseError[]
     *
     * @Serializer\SerializedName("errors")
     * @Serializer\Type("ArrayCollection<App\Dto\Api\V1\Response\ErrorResponseError>")
     *
     * @SWG\Property(description="Error collection (i.e. validation errors)")
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