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
     * @var string
     */
    protected string $password;

    /**
     * UserRegisterBeforeEvent constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}