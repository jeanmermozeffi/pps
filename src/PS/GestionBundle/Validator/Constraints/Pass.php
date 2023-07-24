<?php

namespace PS\GestionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Pass extends Constraint
{

    /**
     * @var string
     */
    public $messagePassNotFound = 'pass.id_pin_not_found';
   
    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
