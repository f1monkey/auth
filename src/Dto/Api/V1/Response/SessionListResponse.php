<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class SessionListResponse
 *
 * @package App\Dto\Api\V1\Response
 */
class SessionListResponse
{
    /**
     * @var Collection<int, SessionResponse>
     *
     * @Serializer\SerializedName("items")
     * @Serializer\Type("ArrayCollection<App\Dto\Api\V1\Response\SessionResponse>")
     */
    protected Collection $items;

    /**
     * SessionListResponse constructor.
     *
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return Collection<int, SessionResponse>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param Collection<int, SessionResponse> $items
     *
     * @return SessionListResponse
     */
    public function setItems(Collection $items): SessionListResponse
    {
        $this->items = $items;

        return $this;
    }
}