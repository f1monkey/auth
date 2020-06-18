<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private string $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private string $usernameCanonical;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max="255")
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max="255")
     */
    private string $emailCanonical;

    /**
     * User constructor.
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
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        $this->usernameCanonical = (string)mb_strtolower($username);

        return $this;
    }

    /**
     * @return string
     */
    public function getUsernameCanonical(): string
    {
        return $this->usernameCanonical;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        $this->emailCanonical = (string)mb_strtolower($email);

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailCanonical(): string
    {
        return $this->emailCanonical;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword()
    {

    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }
}
