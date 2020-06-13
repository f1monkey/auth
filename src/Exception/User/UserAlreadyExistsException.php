<?php
declare(strict_types=1);

namespace App\Exception\User;

use RuntimeException;

/**
 * Class UserAlreadyExistsException
 *
 * @package App\Exception\User
 */
class UserAlreadyExistsException extends RuntimeException implements UserExceptionInterface
{

}