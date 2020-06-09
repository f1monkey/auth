<?php
declare(strict_types=1);

namespace App\Tests\unit\EventListener\Doctrine;

use App\Entity\User;
use App\EventListener\Doctrine\UserPasswordEncodeListener;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;
use RuntimeException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class UserPasswordEncodeListenerTest
 *
 * @package App\Tests\unit\EventListener\Doctrine
 */
class UserPasswordEncodeListenerTest extends Unit
{
    /**
     * @throws BadCredentialsException
     * @throws ExpectationFailedException
     * @throws RuntimeException
     */
    public function testCanEncodeUserPasswordOnPrePersist()
    {
        $user = new User();
        $user->setPlainPassword('password');

        $expected = 'password';
        $service = $this->createUserPasswordEncodeListener(
            1,
            $expected
        );
        $service->prePersist($user);
        $this->assertSame($expected, $user->getPassword());
    }

    /**
     * @throws BadCredentialsException
     * @throws ExpectationFailedException
     * @throws RuntimeException
     */
    public function testCanEncodeChangedUserPasswordOnPreUpdate()
    {
        $user = new User();
        $user->setPlainPassword('password');

        $expected = 'password';
        $service = $this->createUserPasswordEncodeListener(
            1,
            $expected
        );
        $service->preUpdate($user);
        $this->assertSame($expected, $user->getPassword());
    }

    /**
     * @throws ExpectationFailedException
     * @throws RuntimeException
     * @throws BadCredentialsException
     */
    public function testCannotEncodeUnchangedUserPasswordOnPreUpdate()
    {
        $password = 'initialEncodedPassword';
        $user = new User();
        $user->setPassword($password);

        $service = $this->createUserPasswordEncodeListener(
            0,
            'password'
        );
        $service->preUpdate($user);
        $this->assertSame($password, $user->getPassword());

    }

    /**
     * @param int    $expectedCallCount
     * @param string $encodedPassword
     *
     * @return UserPasswordEncodeListener
     * @throws Exception
     */
    protected function createUserPasswordEncodeListener(
        int $expectedCallCount,
        string $encodedPassword
    ): UserPasswordEncodeListener
    {
        $encoder = $this->makeEmpty(
            PasswordEncoderInterface::class,
            [
                'encodePassword' => Expected::exactly($expectedCallCount, $encodedPassword),
            ]
        );
        /** @var EncoderFactoryInterface $encoderFactory */
        $encoderFactory = $this->makeEmpty(
            EncoderFactoryInterface::class,
            [
                'getEncoder' => $encoder,
            ]
        );

        return new UserPasswordEncodeListener($encoderFactory);
    }
}