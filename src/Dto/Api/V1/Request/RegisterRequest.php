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
 *     message="User with this email already exists."
 * )
 * @UniqueEntity(
 *     entityClass=User::class,
 *     fields={"email":"email"},
 *     message="User with this email already exists."
 * )
 */
class RegisterRequest implements V1RequestInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
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