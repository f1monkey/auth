<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\RefreshTokenRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\AbstractRefreshToken;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class JwtRefreshToken
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=RefreshTokenRepository::class)
 * @ORM\Table(
 *     name="refresh_token",
 *     indexes={
 *         @ORM\Index(name="idx_refresh_token_username", columns={"username"})
 *     }
 * )
 * @UniqueEntity("refreshToken")
 */
class RefreshToken extends AbstractRefreshToken implements HasCreatedAtInterface
{
    use HasCreatedAtTrait;

    /**
     * @var string|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    protected ?string $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ip", type="string", length=50, nullable=true)
     */
    protected ?string $ip;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=true)
     */
    protected ?string $userAgent;

    /**
     * RefreshToken constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     *
     * @return RefreshToken
     */
    public function setIp(?string $ip): RefreshToken
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * @param string|null $userAgent
     *
     * @return RefreshToken
     */
    public function setUserAgent(?string $userAgent): RefreshToken
    {
        $this->userAgent = $userAgent;

        return $this;
    }
}