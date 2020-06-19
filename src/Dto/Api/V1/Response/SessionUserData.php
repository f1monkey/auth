<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;

/**
 * Class SessionUserData
 *
 * @package App\Dto\Api\V1\Response
 */
class SessionUserData
{
    /**
     * @var string|null
     *
     * @Serializer\SerializedName("browser")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Client browser", type="string")
     */
    protected ?string $browser;

    /**
     * @var string|null
     *
     * @Serializer\SerializedName("platform")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Client platform type", type="string")
     */
    protected ?string $platform;

    /**
     * @var string|null
     *
     * @Serializer\SerializedName("ip")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Client IP address", type="string")
     */
    protected ?string $ip;

    /**
     * @return string|null
     */
    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    /**
     * @param string|null $browser
     *
     * @return SessionUserData
     */
    public function setBrowser(?string $browser): SessionUserData
    {
        $this->browser = $browser;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    /**
     * @param string|null $platform
     *
     * @return SessionUserData
     */
    public function setPlatform(?string $platform): SessionUserData
    {
        $this->platform = $platform;

        return $this;
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
     * @return SessionUserData
     */
    public function setIp(?string $ip): SessionUserData
    {
        $this->ip = $ip;

        return $this;
    }
}