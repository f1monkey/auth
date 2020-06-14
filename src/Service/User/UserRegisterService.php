<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Event\User\UserRegisterAfterEvent;
use App\Event\User\UserRegisterBeforeEvent;
use App\Exception\Entity\EntityNotFoundException;
use App\Exception\User\UserAlreadyExistsException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class UserRegisterService
 *
 * @package App\Service\User
 */
class UserRegisterService implements UserRegisterServiceInterface
{
    /**
     * @var UserManagerInterface
     */
    protected UserManagerInterface $userManager;

    /**
     * @var EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * UserRegisterService constructor.
     *
     * @param UserManagerInterface     $userManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserManagerInterface $userManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->userManager     = $userManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $username
     * @param $password
     *
     * @return User
     * @throws UserAlreadyExistsException
     */
    public function register(string $username): User
    {
        try {
            $this->userManager->getByUsername($username);
        } catch (EntityNotFoundException $e) {
            $this->eventDispatcher->dispatch(new UserRegisterBeforeEvent($username));

            $user = $this->userManager->create($username);
            $this->userManager->save($user);

            $this->eventDispatcher->dispatch(new UserRegisterAfterEvent($user));

            return $user;
        }

        throw new UserAlreadyExistsException(sprintf('User "%s" already registered', $username));
    }
}