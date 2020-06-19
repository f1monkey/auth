<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\RefreshToken;
use App\Entity\User;
use App\Exception\Entity\EntityNotFoundException;
use App\Repository\RefreshTokenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserSessionManager
 *
 * @package App\Service\User
 */
class UserSessionManager implements UserSessionManagerInterface
{
    /**
     * @var RefreshTokenRepository
     */
    protected RefreshTokenRepository $repository;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * UserSessionManager constructor.
     *
     * @param RefreshTokenRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(RefreshTokenRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em         = $em;
    }

    /**
     * @param User $user
     *
     * @return Collection|RefreshToken[]
     */
    public function getByUser(User $user): Collection
    {
        return new ArrayCollection(
            $this->repository->findByUsername($user->getUsernameCanonical())
        );
    }

    /**
     * @param User   $user
     * @param string $id
     *
     * @return RefreshToken
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function getById(User $user, string $id): RefreshToken
    {
        $result = $this->repository->findByIdAndUsername($id, $user->getUsernameCanonical());
        if ($result === null) {
            throw new EntityNotFoundException(
                sprintf('Refresh token #%s for user "%s" not found', $id, $user->getUsernameCanonical())
            );
        }

        return $result;
    }

    /**
     * @param RefreshToken $refreshToken
     */
    public function delete(RefreshToken $refreshToken): void
    {
        $this->em->remove($refreshToken);
        $this->em->flush();
    }
}