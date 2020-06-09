<?php
declare(strict_types=1);

namespace App\Tests\integration;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use IntegrationTester;

/**
 * Class AbstractIntegrationTestCase
 *
 * @package App\Tests\integration
 */
abstract class AbstractIntegrationTestCase extends Unit
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @var IntegrationTester
     */
    protected IntegrationTester $tester;

    final public function _before()
    {
        $this->em = $this->tester->grabService(EntityManagerInterface::class);
        $this->doBefore();
    }

    protected function doBefore()
    {

    }
}