<?php
declare(strict_types=1);

namespace App\Factory\AuthCode;

use App\Entity\AuthCode;
use App\Entity\User;
use App\Service\AuthCode\AuthCodeGeneratorInterface;
use DateTimeImmutable;
use Exception;

/**
 * Class AuthCodeEntityFactory
 *
 * @package App\Factory\AuthCode
 */
class AuthCodeEntityFactory implements AuthCodeEntityFactoryInterface
{
    /**
     * @var AuthCodeGeneratorInterface
     */
    protected AuthCodeGeneratorInterface $codeGenerator;

    /**
     * AuthCodeEntityFactory constructor.
     *
     * @param AuthCodeGeneratorInterface $codeGenerator
     */
    public function __construct(AuthCodeGeneratorInterface $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * @param User $user
     * @param int  $lifetime
     *
     * @return AuthCode
     * @throws Exception
     */
    public function createForUser(User $user, int $lifetime): AuthCode
    {
        $date   = new DateTimeImmutable(sprintf('+%s seconds', $lifetime));
        $result = new AuthCode();
        $result->setCode($this->codeGenerator->generate())
               ->setParentUser($user)
               ->setInvalidateAt($date);

        return $result;
    }
}