<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Exception\Entity\EntityNotFoundException;
use App\Service\User\UserManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueUsernameValidator
 *
 * @package App\Validator\Constraints
 */
class UniqueUsernameValidator extends ConstraintValidator
{
    /**
     * @var UserManagerInterface
     */
    protected UserManagerInterface $userManager;

    /**
     * UniqueUsernameValidator constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param mixed      $value The value that should be validated
     * @param Constraint $constraint
     *
     * @throws UnexpectedValueException
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$constraint instanceof UniqueUsername) {
            throw new UnexpectedTypeException($value, UniqueUsername::class);
        }

        try {
            $this->userManager->getByUsernameOrEmail($value);
            $this->context->buildViolation($constraint->message, ['{{username}}' => $value])->addViolation();
        } catch (EntityNotFoundException $e) {
        }
    }
}