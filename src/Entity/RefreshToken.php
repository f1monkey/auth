<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\AbstractRefreshToken;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class JwtRefreshToken
 *
 * @package App\Entity
 *
 * @ORM\Entity()
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
     * @var int|null
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected ?int $id;

    /**
     * RefreshToken constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
}