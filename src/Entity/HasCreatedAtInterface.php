<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;

/**
 * Interface HasCreatedAtInterface
 *
 * @package App\Entity
 */
interface HasCreatedAtInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface;
}