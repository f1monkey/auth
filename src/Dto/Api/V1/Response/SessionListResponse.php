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
     * @var Collection|SessionResponse[]
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
     * @return SessionResponse[]|Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param SessionResponse[]|Collection $items
     *
     * @return SessionListResponse
     */
    public function setItems(Collection $items): SessionListResponse
    {
        $this->items = $items;

        return $this;
    }
}