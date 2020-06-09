<?php
declare(strict_types=1);

namespace App\Tests\_support\Extension;

use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Exception\ModuleRequireException;
use Codeception\Extension;
use Codeception\Module\Cli;

/**
 * Class ClearSymfonyCache
 *
 * @package App\Tests\_support\Extension
 */
class ClearCacheExtension extends Extension
{
    public static $events = [
        Events::TEST_BEFORE => 'beforeTest',
    ];

    /**
     * @param TestEvent $event
     *
     * @throws ModuleRequireException
     */
    public function beforeTest(TestEvent $event)
    {
        /** @var Cli $cli */
        $cli = $this->getModule('Cli');

        codecept_debug('Clearing cache pools...');

        $pools = [];

        foreach ($pools as $pool) {
            $cli->runShellCommand(sprintf('bin/console --env=test cache:pool:clear %s', $pool));
        }
    }
}

