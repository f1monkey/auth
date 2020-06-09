<?php
declare(strict_types=1);

namespace App\Exception\Entity;

use RuntimeException;

/**
 * Class EntityNotFoundException
 *
 * @package App\Exception\Entity
 */
class EntityNotFoundException extends RuntimeException implements EntityExceptionInterface
{

}