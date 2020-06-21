<?php
declare(strict_types=1);

namespace App\EventListener\User;

use App\Event\User\UserRegisterAfterEvent;
use App\Exception\AuthCode\TooManyAuthCodesException;
use App\Service\AuthCode\AuthCodeManagerInterface;

/**
 * Class UserRegisterEventListener
 *
 * @package App\EventListener\User
 */
class UserRegisterEventListener
{
    /**
     * @var AuthCodeManagerInterface
     */
    protected AuthCodeManagerInterface $authCodeManager;

    /**
     * UserRegisterEventListener constructor.
     *
     * @param AuthCodeManagerInterface $authCodeManager
     */
    public function __construct(AuthCodeManagerInterface $authCodeManager)
    {
        $this->authCodeManager = $authCodeManager;
    }

    /**
     * @param UserRegisterAfterEvent $event
     *
     * @throws TooManyAuthCodesException
     */
    public function onUserRegister(UserRegisterAfterEvent $event): void
    {
        $user = $event->getUser();
        $this->authCodeManager->createForUser($user);
    }
}