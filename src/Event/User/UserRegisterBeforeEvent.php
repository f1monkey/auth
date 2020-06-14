<?php
declare(strict_types=1);

namespace App\Event\User;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UserRegisterBeforeEvent
 *
 * @package App\Event\User
 */
class UserRegisterBeforeEvent extends Event
{
    /**
     * @var string
     */
    protected string $username;

    /**
     * UserRegisterBeforeEvent constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}