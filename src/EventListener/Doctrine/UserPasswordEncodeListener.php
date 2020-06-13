<?php
declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\User;
use RuntimeException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class UserPasswordEncoder
 *
 * @package SberLogistic\EventSubscriber\Doctrine
 */
class UserPasswordEncodeListener
{
    /**
     * @var EncoderFactoryInterface
     */
    protected EncoderFactoryInterface $encoderFactory;

    /**
     * UserPasswordSubscriber constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param User $user
     *
     * @throws BadCredentialsException
     * @throws RuntimeException
     */
    public function prePersist(User $user)
    {
        $this->encodeUserPassword($user);
    }

    /**
     * @param User $user
     *
     * @throws BadCredentialsException
     * @throws RuntimeException
     */
    public function preUpdate(User $user)
    {
        $this->encodeUserPassword($user);
    }

    /**
     * @param User $user
     *
     * @throws BadCredentialsException
     * @throws RuntimeException
     */
    protected function encodeUserPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if (!$plainPassword) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
        $user->setSalt($salt);

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}
