<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class UserFixtures
 *
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    public const USER_1_USERNAME = 'user';
    public const USER_1_PASSWORD = 'password';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername(static::USER_1_USERNAME)
             ->setPlainPassword(static::USER_1_PASSWORD);
        $manager->persist($user);

        $manager->flush();
    }
}
