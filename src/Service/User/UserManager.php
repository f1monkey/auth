<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\Entity\EntityNotFoundException;
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
        $this->em             = $em;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $uuid
     *
     * @return User
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function getById(string $uuid): User
    {
        $result = $this->userRepository->findById($uuid);

        if ($result === null) {
            throw new EntityNotFoundException(
                sprintf('User #%s not found', $uuid)
            );
        }

        return $result;
    }

    /**
     * @param string $username
     *
     * @return User
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function getByUsername(string $username): User
    {
        $result = $this->userRepository->findByUsername($username);

        if ($result === null) {
            throw new EntityNotFoundException(
                sprintf('User with username "%s" not found', $username)
            );
        }

        return $result;
    }

    /**
     * @param string $username
     * @param string $email
     *
     * @return User
     */
    public function create(string $username, string $email): User
    {
        $user = new User();
        $user->setUsername($username)
             ->setEmail($email);

        return $user;
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