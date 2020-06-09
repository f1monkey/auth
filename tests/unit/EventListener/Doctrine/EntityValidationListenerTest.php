<?php
declare(strict_types=1);

namespace App\Tests\unit\EventListener\Doctrine;

use App\EventListener\Doctrine\EntityValidationListener;
use App\Exception\Entity\EntityValidationException;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Exception;
use stdClass;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class EntityValidationListenerTest
 *
 * @package App\Tests\unit\EventListener\Doctrine
 */
class EntityValidationListenerTest extends Unit
{
    /**
     * @throws EntityValidationException
     * @throws Exception
     */
    public function testCanPassValidEntityOnPrePersist()
    {
        $service = $this->createListener(0);

        $service->prePersist($this->createEventObject());
    }

    /**
     * @throws EntityValidationException
     * @throws Exception
     */
    public function testCannotPassInvalidEntityOnPrePersist()
    {
        $service = $this->createListener(1);

        $this->expectException(EntityValidationException::class);
        $service->prePersist($this->createEventObject());
    }

    /**
     * @throws EntityValidationException
     * @throws Exception
     */
    public function testCantPassValidEntityOnPreUpdate()
    {
        $service = $this->createListener(0);

        $service->preUpdate($this->createEventObject());
    }

    /**
     * @throws EntityValidationException
     * @throws Exception
     */
    public function testCannotPassInvalidEntityOnPreUpdate()
    {
        $service = $this->createListener(1);

        $this->expectException(EntityValidationException::class);
        $service->preUpdate($this->createEventObject());
    }

    /**
     * @param int $violationCount
     *
     * @return EntityValidationListener
     * @throws Exception
     */
    protected function createListener(int $violationCount): EntityValidationListener
    {
        $violations = $this->makeEmpty(
            ConstraintViolationList::class,
            [
                'count' => $violationCount,
            ]
        );
        /** @var ValidatorInterface $validator */
        $validator = $this->makeEmpty(
            ValidatorInterface::class,
            [
                'validate' => Expected::once($violations),
            ]
        );

        return new EntityValidationListener($validator);
    }

    /**
     * @return LifecycleEventArgs
     * @throws Exception
     */
    protected function createEventObject(): LifecycleEventArgs
    {
        /** @var LifecycleEventArgs $args */
        $args = $this->makeEmpty(
            LifecycleEventArgs::class,
            [
                'getEntity' => new stdClass(),
            ]
        );

        return $args;
    }
}