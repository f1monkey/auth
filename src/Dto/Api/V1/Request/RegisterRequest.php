<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Request;

use App\Dto\Api\RequestInterface;
use App\Validator\Constraints\UniqueUsername;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterRequest
 *
 * @package App\Dto\Api\V1\Request
 */
class RegisterRequest implements RequestInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @UniqueUsername()
     *
     * @Serializer\SerializedName("username")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Username", example="user")
     */
    protected ?string $username = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max="255")
     * @UniqueUsername()
     *
     * @Serializer\SerializedName("email")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="E-mail", example="user@example.com")
     */
    protected ?string $email = null;

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
     * @return RegisterRequest
     */
    public function setUsername(?string $username): RegisterRequest
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return RegisterRequest
     */
    public function setEmail(?string $email): RegisterRequest
    {
        $this->email = $email;

        return $this;
    }
}