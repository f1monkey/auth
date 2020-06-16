<?php
declare(strict_types=1);

namespace App\Event\AuthCode;

use App\Entity\AuthCode;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class AuthCodeCreateAfterEvent
 *
 * @package App\Event\AuthCode
 */
class AuthCodeCreateAfterEvent extends Event
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * @var AuthCode
     */
    protected AuthCode $code;

    /**
     * AuthCodeCreateBeforeEvent constructor.
     *
     * @param User     $user
     * @param AuthCode $code
     */
    public function __construct(User $user, AuthCode $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return AuthCode
     */
    public function getCode(): AuthCode
    {
        return $this->code;
    }
}