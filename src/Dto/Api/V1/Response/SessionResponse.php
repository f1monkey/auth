<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

use DateTimeInterface;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SessionResponse
 *
 * @package App\Dto\Api\V1\Response
 */
class SessionResponse
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Session id", example="961e7e0a-945f-4956-ba1a-ac06c8c3d6b2")
     */
    protected string $id;

    /**
     * @var DateTimeInterface
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("createdAt")
     * @Serializer\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @SWG\Property(title="Created at", example="2020-06-19T16:14:11+00:00")
     */
    protected DateTimeInterface $createdAt;

    /**
     * @var SessionUserData
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("userData")
     * @Serializer\Type("App\Dto\Api\V1\Response\SessionUserData")
     *
     * @SWG\Property(title="Information about this session")
     */
    protected SessionUserData $userData;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return SessionResponse
     */
    public function setId(string $id): SessionResponse
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     *
     * @return SessionResponse
     */
    public function setCreatedAt(DateTimeInterface $createdAt): SessionResponse
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return SessionUserData
     */
    public function getUserData(): SessionUserData
    {
        return $this->userData;
    }

    /**
     * @param SessionUserData $userData
     *
     * @return SessionResponse
     */
    public function setUserData(SessionUserData $userData): SessionResponse
    {
        $this->userData = $userData;

        return $this;
    }
}