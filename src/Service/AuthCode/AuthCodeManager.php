<?php
declare(strict_types=1);

namespace App\Service\AuthCode;

use App\Entity\AuthCode;
use App\Entity\User;
use App\Event\AuthCode\AuthCodeCreateAfterEvent;
use App\Event\AuthCode\AuthCodeCreateBeforeEvent;
use App\Exception\AuthCode\TooManyAuthCodesException;
use App\Exception\Entity\EntityNotFoundException;
use App\Factory\AuthCode\AuthCodeEntityFactoryInterface;
use App\Repository\AuthCodeRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class AuthCodeManager
 *
 * @package App\Service\AuthCode
 */
class AuthCodeManager implements AuthCodeManagerInterface
{
    /**
     * @var AuthCodeEntityFactoryInterface
     */
    protected AuthCodeEntityFactoryInterface $factory;

    /**
     * @var AuthCodeRepository
     */
    protected AuthCodeRepository $repository;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @var EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @var int
     */
    protected int $maxPerUser;

    /**
     * @var int
     */
    protected int $codeLifetime;

    /**
     * EmailTokenManager constructor.
     *
     * @param AuthCodeEntityFactoryInterface $factory
     * @param AuthCodeRepository             $repository
     * @param EntityManagerInterface         $em
     * @param EventDispatcherInterface       $eventDispatcher
     * @param int                            $maxPerUser
     * @param int                            $codeLifetime
     */
    public function __construct(
        AuthCodeEntityFactoryInterface $factory,
        AuthCodeRepository $repository,
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher,
        int $maxPerUser = 5,
        int $codeLifetime = 900
    )
    {
        $this->factory         = $factory;
        $this->repository      = $repository;
        $this->em              = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->maxPerUser      = $maxPerUser;
        $this->codeLifetime    = $codeLifetime;
    }

    /**
     * @param User $user
     *
     * @return AuthCode
     * @throws TooManyAuthCodesException
     */
    public function createForUser(User $user): AuthCode
    {
        $this->checkIfCanCreate($user);

        $this->eventDispatcher->dispatch(new AuthCodeCreateBeforeEvent($user));
        $result = $this->factory->createForUser($user, $this->codeLifetime);
        $this->save($result);
        $this->eventDispatcher->dispatch(new AuthCodeCreateAfterEvent($user, $result));

        return $result;
    }

    /**
     * @param User   $user
     * @param string $token
     *
     * @return AuthCode
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function get(User $user, string $token): AuthCode
    {
        $result = $this->repository->findOneActiveByUserAndCode($user->getUsername(), $token);
        if ($result === null) {
            throw new EntityNotFoundException(
                sprintf(
                    'Token "%s" for user "%s" is not found',
                    $token,
                    $user->getUsername()
                )
            );
        }

        return $result;
    }

    /**
     * @param User $user
     */
    public function deleteByUser(User $user): void
    {
        $this->repository->deleteByUser($user->getId());
    }

    /**
     * @param AuthCode $token
     */
    protected function save(AuthCode $token): void
    {
        $this->em->persist($token);
        $this->em->flush();
    }

    /**
     * @param User $user
     *
     * @throws TooManyAuthCodesException
     */
    protected function checkIfCanCreate(User $user): void
    {
        if ($this->repository->countActiveByUser($user->getUsername()) >= $this->maxPerUser) {
            throw new TooManyAuthCodesException(
                sprintf('Auth code count limit reached for user "%s"', $user->getUsername())
            );
        }
    }

    /**
     * @param DateTimeInterface $from
     */
    public function deleteOutdated(DateTimeInterface $from): void
    {
        $this->repository->deleteOutdated($from);
    }
}