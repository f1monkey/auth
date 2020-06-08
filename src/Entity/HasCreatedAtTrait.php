<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait HasCreatedAtTrait
 *
 * @package App\Entity
 */
trait HasCreatedAtTrait
{
    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private DateTimeInterface $createdAt;

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}