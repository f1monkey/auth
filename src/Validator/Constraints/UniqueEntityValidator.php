<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException as PropertyAccessUnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class UniqueEntityValidator
 *
 * @package App\Validator\Constraints
 */
class UniqueEntityValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @var PropertyAccessorInterface
     */
    protected PropertyAccessorInterface $propertyAccessor;

    /**
     * UniqueEntityValidator constructor.
     *
     * @param EntityManagerInterface    $em
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(EntityManagerInterface $em, PropertyAccessorInterface $propertyAccessor)
    {
        $this->em               = $em;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     *
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException($constraint, UniqueEntity::class);
        }

        try {
            $repository = $this->em->getRepository($constraint->entityClass);

            $criteria = [];
            foreach ($constraint->fields as $objectField => $entityField) {
                $criteria[$entityField] = $this->propertyAccessor->getValue($value, $objectField);
            }

            $result = $repository->findOneBy($criteria);

            if ($result) {
                $this->context->buildViolation(
                    $constraint->message,
                    [
                        '{{fields}}' => implode(', ', array_keys($constraint->fields)),
                    ]
                )->addViolation();
            }
        } catch (PropertyAccessUnexpectedTypeException|AccessException $e) {
            /**
             * skip validation if property is not passed in request
             * because class constraints are checked prior to property constraints
             */
        }
    }
}