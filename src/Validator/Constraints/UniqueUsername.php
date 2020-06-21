<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueUsername
 *
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class UniqueUsername extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'User "{{username}}" already registered';
}