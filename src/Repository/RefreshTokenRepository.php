<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\RefreshToken;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 *
 * @package App\Repository
 *
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshToken[]    findAll()
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshTokenRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    /**
     * @param string $id
     * @param string $username
     *
     * @return RefreshToken|null
     * @throws NonUniqueResultException
     */
    public function findByIdAndUsername(string $id, string $username): ?RefreshToken
    {
        return $this->createBaseQb()
                    ->andWhere('rt.username = :username')
                    ->andWhere('rt.id = :id')
                    ->setParameter('username', $username)
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @param string $token
     *
     * @return RefreshToken|null
     * @throws NonUniqueResultException
     */
    public function findByTokenValue(string $token): ?RefreshToken
    {
        return $this->createBaseQb()
                    ->andWhere('rt.refreshToken = :token')
                    ->setParameter('token', $token)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @param string $username
     *
     * @return RefreshToken[]
     */
    public function findByUsername(string $username): array
    {
        return $this->createBaseQb()
                    ->andWhere('rt.username = :username')
                    ->setParameter('username', $username)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param DateTimeInterface $datetime
     *
     * @return RefreshToken[]
     */
    public function findInvalid(DateTimeInterface $datetime = null): array
    {
        if ($datetime === null) {
            $datetime = new DateTime();
        }

        return $this->createBaseQb()
                    ->where('rt.valid < :datetime')
                    ->setParameter(':datetime', $datetime->format(DATE_ATOM))
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return QueryBuilder
     */
    protected function createBaseQb(): QueryBuilder
    {
        return $this->createQueryBuilder('rt')
                    ->select('rt');
    }
}
