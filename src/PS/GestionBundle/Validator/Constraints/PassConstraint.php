<?php

namespace PS\GestionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PassConstraint extends Constraint
{

    /**
     * @var string
     */
    public $messagePassNotFound = 'pass.id_pin_not_found';
    /**
     * @var string
     */
    public $messageNotSameCorporate = 'pass.not_same_corporate';
    /**
     * @var string
     */
    public $messagePassNotSameCorporate = 'pass.not_in_corporate';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
