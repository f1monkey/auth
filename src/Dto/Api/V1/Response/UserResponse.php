<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserResponse
 *
 * @package App\Dto\Api\V1\Response
 */
class UserResponse
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("username")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Username", example="user")
     */
    protected string $username;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("email")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="E-mail", example="user@example.com")
     */
    protected string $email;

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
     * @return UserResponse
     */
    public function setUsername(string $username): UserResponse
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
     * @return UserResponse
     */
    public function setEmail(string $email): UserResponse
    {
        $this->email = $email;

        return $this;
    }
}