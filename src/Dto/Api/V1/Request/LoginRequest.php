<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Request;

use App\Dto\Api\RequestInterface;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LoginRequest
 *
 * @package App\Dto\Api\V1\Request
 */
class LoginRequest implements RequestInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("username")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Username or email", example="user")
     */
    protected ?string $username;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     *
     * @return LoginRequest
     */
    public function setUsername(?string $username): LoginRequest
    {
        $this->username = $username;

        return $this;
    }
}