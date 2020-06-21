<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Request;

use App\Dto\Api\RequestInterface;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LoginConfirmRequest
 *
 * @package App\Dto\Api\V1\Request
 */
class LoginConfirmRequest implements RequestInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("username")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Username or email", example="user")
     */
    protected string $username = '';

    /**
     * @var string
     *
     * @Serializer\SerializedName("authCode")
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @SWG\Property(title="Auth code", example="123456")
     */
    protected string $authCode = '';

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
     * @return LoginConfirmRequest
     */
    public function setUsername(string $username): LoginConfirmRequest
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * @param string $authCode
     *
     * @return LoginConfirmRequest
     */
    public function setAuthCode(string $authCode): LoginConfirmRequest
    {
        $this->authCode = $authCode;

        return $this;
    }
}