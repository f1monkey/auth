<?php
declare(strict_types=1);

namespace App\Tests\_support\Extension;

use Codeception\Event\SuiteEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Exception\ModuleRequireException;
use Codeception\Extension;
use Codeception\Module\Cli;
use Codeception\Module\Symfony;
use Codeception\Util\Fixtures;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DatabasePrepareExtension
 *
 * @package App\Tests\_support\Extension
 */
class DatabasePrepareExtension extends Extension
{
    public static $events = [
        Events::SUITE_BEFORE => 'beforeSuite',
        Events::TEST_BEFORE  => 'beforeTest',
        Events::TEST_AFTER   => 'afterTest',
    ];

    /**
     * @param SuiteEvent $event
     *
     * @throws ModuleRequireException
     */
    public function beforeSuite(SuiteEvent $event)
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        /** @var Cli $cli */
        $cli = $this->getModule('Cli');

        $env = $symfony->_getContainer()->getParameter('kernel.environment');
        $cli->runShellCommand(
            sprintf(
                'bin/console doctrine:schema:drop --env=%s --full-database --force',
                $env
            )
        );
        $cli->seeResultCodeIs(0);
        $cli->runShellCommand(
            sprintf(
                'bin/console doctrine:schema:update --env=%s --force',
                $env
            )
        );
        $cli->seeResultCodeIs(0);
        $cli->runShellCommand(
            sprintf(
                'bin/console doctrine:query:sql --env=%s "CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\""',
                $env
            )
        );
        $cli->seeResultCodeIs(0);
    }

    /**
     * @param TestEvent $event
     *
     * @throws ModuleRequireException
     */
    public function beforeTest(TestEvent $event)
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        /** @var EntityManagerInterface $em */
        $em = $symfony->grabService(EntityManagerInterface::class);
        $em->getConnection()->beginTransaction();
    }

    /**
     * @throws ModuleRequireException
     * @throws ConnectionException
     */
    public function afterTest()
    {
        Fixtures::cleanup();
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        /** @var EntityManagerInterface $em */
        $em = $symfony->grabService(EntityManagerInterface::class);
        $em->getConnection()->rollBack();
    }
}
