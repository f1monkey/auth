<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Request;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LoginRequest
 *
 * @package App\Dto\Api\V1\Request
 */
class LoginRequest implements V1RequestInterface
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
     * @Serializer\SerializedName("password")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Password", example="password")
     */
    protected string $password;

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
     * @return LoginRequest
     */
    public function setUsername(string $username): LoginRequest
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return LoginRequest
     */
    public function setPassword(string $password): LoginRequest
    {
        $this->password = $password;

        return $this;
    }
}