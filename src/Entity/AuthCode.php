<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\AuthCodeRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class AuthCode
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=AuthCodeRepository::class)
 * @ORM\Table(
 *     name="auth_code",
 *     uniqueConstraints={
 *          @UniqueConstraint(
 *              name="unique_user_code",
 *              columns={
 *                  "parent_user_id",
 *                  "code"
 *              }
 *          )
 *     }
 * )
 */
class AuthCode implements HasCreatedAtInterface
{
    use HasCreatedAtTrait;

    /**
     * @var string|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $id;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetimetz_immutable")
     */
    private DateTimeInterface $createdAt;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetimetz_immutable")
     */
    private DateTimeInterface $invalidateAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $code;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $parentUser;

    /**
     * EmailToken constructor.
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
     * @return DateTimeInterface
     */
    public function getInvalidateAt(): DateTimeInterface
    {
        return $this->invalidateAt;
    }

    /**
     * @param DateTimeInterface $invalidateAt
     *
     * @return AuthCode
     */
    public function setInvalidateAt(DateTimeInterface $invalidateAt): AuthCode
    {
        $this->invalidateAt = $invalidateAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return AuthCode
     */
    public function setCode(string $code): AuthCode
    {
        $this->code = (string)mb_strtolower($code);

        return $this;
    }

    /**
     * @return User
     */
    public function getParentUser(): User
    {
        return $this->parentUser;
    }

    /**
     * @param User $parentUser
     *
     * @return AuthCode
     */
    public function setParentUser(User $parentUser): AuthCode
    {
        $this->parentUser = $parentUser;

        return $this;
    }
}
