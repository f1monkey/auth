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
    protected string $email;

    /**
     * UserRegisterBeforeEvent constructor.
     *
     * @param string $username
     * @param string $email
     */
    public function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email    = $email;
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
    public function getEmail(): string
    {
        return $this->email;
    }
}