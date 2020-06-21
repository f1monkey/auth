<?php
declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Exception\Entity\EntityValidationException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class EntityValidationListener
 *
 * @package App\EventListener\Doctrine
 */
class EntityValidationListener
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * EntityValidationListener constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws EntityValidationException
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->validate($args->getEntity());
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws EntityValidationException
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->validate($args->getEntity());
    }

    /**
     * @param object $entity
     *
     * @throws EntityValidationException
     */
    public function validate(object $entity): void
    {
        $violations = $this->validator->validate($entity);
        if ($violations->count()) {
            throw new EntityValidationException($violations);
        }
    }
}