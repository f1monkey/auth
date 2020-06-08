<?php
declare(strict_types=1);

namespace App\Exception\User;

use RuntimeException;

/**
 * Class UserNotFoundException
 *
 * @package App\Exception\User
 */
class UserNotFoundException extends RuntimeException implements UserExceptionInterface
{

}