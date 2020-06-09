<?php
declare(strict_types=1);

namespace App\Tests\_support\Helper;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait RepositoryTrait
 *
 * @package App\Tests\_support
 */
trait RepositoryTrait
{
    /**
     * @param object $entity
     */
    public function haveInRepository(object $entity): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getModule('Symfony')->grabService(EntityManagerInterface::class);
        $em->persist($entity);
        $em->flush();
    }

    /**
     * @param string $class
     * @param        $id
     *
     * @return object
     */
    public function grabEntityFromRepository(string $class, $id): object
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getModule('Symfony')->grabService(EntityManagerInterface::class);

        return $em->getRepository($class)
                  ->find($id);
    }
}