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
}