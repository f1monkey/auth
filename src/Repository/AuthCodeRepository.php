<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\AuthCode;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AuthCodeRepository
 *
 * @package App\Repository
 *
 * @method AuthCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthCode[]    findAll()
 * @method AuthCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthCodeRepository extends ServiceEntityRepository
{
    /**
     * AuthTokenRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthCode::class);
    }

    /**
     * @param string $username
     * @param string $code
     *
     * @return AuthCode|null
     * @throws NonUniqueResultException
     */
    public function findOneActiveByUserAndCode(string $username, string $code): ?AuthCode
    {
        return $this->createActiveQb()
                    ->andWhere('u.username = :username')
                    ->andWhere('t.code = :code')
                    ->setParameter('username', $username)
                    ->setParameter('code', $code)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @param string $userId
     *
     * @return void
     */
    public function deleteByUser(string $userId): void
    {
        $this->createQueryBuilder('a')->delete()
             ->where('a.parentUser = :user')
             ->setParameter('user', $userId)
             ->getQuery()
             ->execute();
    }

    /**
     * @param string $username
     *
     * @return int
     */
    public function countActiveByUser(string $username): int
    {
        return $this->createActiveQb()
                    ->andWhere('u.username = :username')
                    ->setParameter('username', $username)
                    ->select('count(t) as cnt')
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    /**
     * @return QueryBuilder
     */
    protected function createActiveQb(): QueryBuilder
    {
        $date = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        return $this->createQueryBuilder('t')
                    ->select('t')
                    ->innerJoin('t.parentUser', 'u')
                    ->andWhere('t.invalidateAt > :date')
                    ->setParameter('date', $date)
                    ->addSelect('u');
    }

    /**
     * @return QueryBuilder
     */
    protected function createBaseQb(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
                    ->select('t')
                    ->innerJoin('t.parentUser', 'u')
                    ->addSelect('u');
    }
}
