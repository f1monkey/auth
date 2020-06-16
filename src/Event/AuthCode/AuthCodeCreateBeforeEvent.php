<?php
declare(strict_types=1);

namespace App\Event\AuthCode;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class AuthCodeCreateBeforeEvent
 *
 * @package App\Event\AuthCode
 */
class AuthCodeCreateBeforeEvent extends Event
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * AuthCodeCreateBeforeEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}