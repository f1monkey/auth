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
}