<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserRepository
 *
 * @package App\Repository
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $uuid
     *
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findById(string $uuid): ?User
    {
        return $this->createBaseQb()
                    ->where('u.id = :uuid')
                    ->setParameter('uuid', $uuid)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @param string $username
     *
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findByUsernameOrEmail(string $username): ?User
    {
        return $this->createBaseQb()
                    ->orWhere('u.usernameCanonical = :username')
                    ->orWhere('u.emailCanonical = :username')
                    ->setParameter('username', mb_strtolower($username))
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @return QueryBuilder
     */
    protected function createBaseQb(): QueryBuilder
    {
        return $this->createQueryBuilder('u')
                    ->select('u');
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username
     *
     * @return UserInterface|null
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername(string $username)
    {
        return $this->findByUsernameOrEmail($username);
    }
}
