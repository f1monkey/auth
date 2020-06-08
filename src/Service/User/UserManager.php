<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserManager
 *
 * @package App\Service\User
 */
class UserManager implements UserManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserRepository         $userRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository
    )
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $uuid
     *
     * @return User
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function getById(string $uuid): User
    {
        $result = $this->userRepository->findById($uuid);

        if ($result === null) {
            throw new UserNotFoundException(
                sprintf('User #%s not found', $uuid)
            );
        }

        return $result;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return User
     */
    public function create(string $username, string $password): User
    {
        $user = new User();
        $user->setUsername($username)
             ->setPlainPassword($password);

        return $user;
    }

    /**
     * @param User   $user
     * @param string $password
     */
    public function updatePassword(User $user, string $password): void
    {
        $user->setPlainPassword($password)
             ->setPassword('');
        $this->save($user);
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}