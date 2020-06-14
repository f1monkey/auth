<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEntity
 *
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class UniqueEntity extends Constraint
{
    /**
     * @var string
     */
    public $entityClass;

    /**
     * @var string[]
     */
    public $fields = [];

    /**
     * @var string
     */
    public $message = 'Combination of {{fields}} is not unique';

    /**
     * @return string[]
     */
    public function getRequiredOptions()
    {
        return ['fields', 'entityClass'];
    }

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}