<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Request;

use App\Dto\Api\RequestInterface;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RefreshRequest
 *
 * @package App\Dto\Api\V1\Request
 */
class RefreshRequest implements RequestInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("refreshToken")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Refresh token", example="ce38f8368ee51f10e8b52006c6b84d643ab9dd1e6a9589a6d5ce327767866a89c98b8983e9d1db7b92fa64f92604c748824cc5bc0500d5f67c2c69b11dc1c37e")
     */
    protected ?string $refreshToken;

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @param string|null $refreshToken
     *
     * @return RefreshRequest
     */
    public function setRefreshToken(?string $refreshToken): RefreshRequest
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}