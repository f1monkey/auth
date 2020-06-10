<?php
declare(strict_types=1);

namespace App\Tests\_support\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Exception\ModuleRequireException;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Trait FixtureLoadTrait
 *
 * @package App\Tests\_support\Helper
 */
trait FixtureLoadTrait
{
    /**
     * Copy from https://github.com/Codeception/module-doctrine2/blob/master/src/Codeception/Module/Doctrine2.php
     *
     * @param      $fixtures
     * @param bool $append
     *
     * @throws ModuleException
     * @throws ModuleRequireException
     */
    public function loadFixtures($fixtures, $append = true)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getModule('Symfony')->grabService(EntityManagerInterface::class);

        if (!class_exists(Loader::class)
            || !class_exists(ORMPurger::class)
            || !class_exists(ORMExecutor::class)) {
            throw new ModuleRequireException(
                __CLASS__,
                'Doctrine fixtures support in unavailable.\n'
                . 'Please, install doctrine/data-fixtures.'
            );
        }

        if (!is_array($fixtures)) {
            $fixtures = [$fixtures];
        }

        $loader = new Loader();

        foreach ($fixtures as $fixture) {
            if (is_string($fixture)) {
                if (!class_exists($fixture)) {
                    throw new ModuleException(
                        __CLASS__,
                        sprintf(
                            'Fixture class "%s" does not exist',
                            $fixture
                        )
                    );
                }

                if (!is_a($fixture, FixtureInterface::class, true)) {
                    throw new ModuleException(
                        __CLASS__,
                        sprintf(
                            'Fixture class "%s" does not inherit from "%s"',
                            $fixture,
                            FixtureInterface::class
                        )
                    );
                }

                try {
                    $fixtureInstance = new $fixture();
                } catch (Exception $e) {
                    throw new ModuleException(
                        __CLASS__,
                        sprintf(
                            'Fixture class "%s" could not be loaded, got %s%s',
                            $fixture,
                            get_class($e),
                            empty($e->getMessage()) ? '' : ': ' . $e->getMessage()
                        )
                    );
                }
            } elseif (is_object($fixture)) {
                if (!$fixture instanceof FixtureInterface) {
                    throw new ModuleException(
                        __CLASS__,
                        sprintf(
                            'Fixture "%s" does not inherit from "%s"',
                            get_class($fixture),
                            FixtureInterface::class
                        )
                    );
                }

                $fixtureInstance = $fixture;
            } else {
                throw new ModuleException(
                    __CLASS__,
                    sprintf(
                        'Fixture is expected to be an instance or class name, inherited from "%s"; got "%s" instead',
                        FixtureInterface::class,
                        is_object($fixture) ? get_class($fixture) ? is_string($fixture) : $fixture : gettype($fixture)
                    )
                );
            }

            try {
                $loader->addFixture($fixtureInstance);
            } catch (Exception $e) {
                throw new ModuleException(
                    __CLASS__,
                    sprintf(
                        'Fixture class "%s" could not be loaded, got %s%s',
                        get_class($fixtureInstance),
                        get_class($e),
                        empty($e->getMessage()) ? '' : ': ' . $e->getMessage()
                    )
                );
            }
        }

        try {
            $purger = new ORMPurger($em);
            $executor = new ORMExecutor($em, $purger);
            $executor->execute($loader->getFixtures(), $append);
        } catch (Exception $e) {
            throw new ModuleException(
                __CLASS__,
                sprintf(
                    'Fixtures could not be loaded, got %s%s',
                    get_class($e),
                    empty($e->getMessage()) ? '' : ': ' . $e->getMessage()
                )
            );
        }
    }
}