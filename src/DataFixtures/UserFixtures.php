<?php
declare(strict_types=1);

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
    public const USER_1_EMAIL = 'user@domain.local';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername(static::USER_1_USERNAME);
        $user->setEmail(static::USER_1_EMAIL);
        $manager->persist($user);

        $manager->flush();
    }
}
