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
     * @var string
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
    protected string $username = '';

    /**
     * @var string
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
    protected string $email = '';

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return RegisterRequest
     */
    public function setUsername(string $username): RegisterRequest
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return RegisterRequest
     */
    public function setEmail(string $email): RegisterRequest
    {
        $this->email = $email;

        return $this;
    }
}