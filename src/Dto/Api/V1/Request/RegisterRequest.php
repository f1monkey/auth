<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Request;

use App\Entity\User;
use App\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterRequest
 *
 * @package App\Dto\Api\V1\Request
 *
 * @UniqueEntity(
 *     entityClass=User::class,
 *     fields={"username":"username"},
 *     message="User with this email already exists"
 * )
 */
class RegisterRequest implements V1RequestInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @Serializer\SerializedName("username")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="E-mail", example="user@example.com")
     */
    protected string $username;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     *
     * @Serializer\SerializedName("password")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Password", example="12345678")
     */
    protected string $password;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\EqualTo(propertyPath="password")
     *
     * @Serializer\SerializedName("passwordConfirm")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Password confirmation", example="12345678")
     */
    protected string $passwordConfirm;

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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return RegisterRequest
     */
    public function setPassword(string $password): RegisterRequest
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordConfirm(): string
    {
        return $this->passwordConfirm;
    }

    /**
     * @param string $passwordConfirm
     *
     * @return RegisterRequest
     */
    public function setPasswordConfirm(string $passwordConfirm): RegisterRequest
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }
}